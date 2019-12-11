<?php


namespace App\Controller;


use App\Common\GxGlobalConfig;
use App\Common\PayWayConst;
use App\Entity\GxOrder;
use App\Entity\UserWallet;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\PlatformWalletServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\yipay\NotifyParams;
use by\infrastructure\base\CallResult;
use Doctrine\DBAL\LockMode;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YiPayController extends AbstractController
{
    protected $logger;
    protected $gxOrderService;
    protected $gxConfig;
    protected $profitGraphService;
    protected $platformWalletService;
    protected $userWalletService;

    public function __construct(
        UserWalletServiceInterface $userWalletService,
        PlatformWalletServiceInterface $platformWalletService,
        ProfitGraphServiceInterface $profitGraphService,
        GxGlobalConfig $gxGlobalConfig,
        GxOrderServiceInterface $gxOrderService, LoggerInterface $logger)
    {
        $this->userWalletService = $userWalletService;
        $this->platformWalletService = $platformWalletService;
        $this->logger = $logger;
        $this->profitGraphService = $profitGraphService;
        $this->gxConfig = $gxGlobalConfig;
        $this->gxOrderService = $gxOrderService;
    }


    /**
     * 支付方式1 - 异步回调
     * @Route("/yipay/notify", name="yipay_notify", methods={"GET","POST"})
     * @param Request $request
     * @return string
     */
    public function notify(Request $request)
    {
        try {

//            $rawData = $request->getContent();
//            $this->logger->error('原始数据'.$rawData);
            $rawData = $request->request->all();
            $this->logger->error('原始数据POST'.json_encode($rawData));
//            $rawData = $request->query->all();
//            $this->logger->error('原始数据GET'.json_encode($rawData));

            $rawData = $request->request->all();
            try {
                $np = new NotifyParams($rawData);
            } catch (Exception $exception) {
                $this->logger->error("notify params invalid");
                return 'notify params invalid';
            }
            $rawData = json_encode($rawData);
            $gxOrder = $this->gxOrderService->info(['order_no' => $np->getOrderNumber()]);
            if (!$gxOrder instanceof GxOrder) {
                $this->logger->error('[订单号不存在]' . ($rawData));
                return 'out_order_id not exists';
            }

            // 签名校验关闭

            $wallet = $this->userWalletService->info(['uid' => $gxOrder->getUid()]);
            if (!$wallet instanceof UserWallet) {
                $this->logger->error('用户' . $gxOrder->getUid() . '的钱包不存在' . ($rawData));
                return '用户' . $gxOrder->getUid() . '的钱包不存在';
            }

            $this->gxOrderService->getEntityManager()->beginTransaction();
            try {
                $this->gxOrderService->findById($gxOrder->getId(), LockMode::PESSIMISTIC_READ);
                if ($gxOrder->getPayStatus() == GxOrder::Paid) {
                    $this->gxOrderService->getEntityManager()->rollback();
                    $this->logger->error('[支付回调] 订单已处理' . $gxOrder->getOrderNo());
                    return new Response('succeed');
                }
                $gxOrder->setPayStatus(GxOrder::Paid);
                $gxOrder->setPaidTime(time());
                $gxOrder->setArrivalAmount($np->getMoney());
                $gxOrder->setSign($np->getToken());
                $gxOrder->setPayRetOrderId($np->getOrderNumber());
                $gxOrder->setPw(PayWayConst::PWYIPAY);
                $gxOrder->setArrivalAmount($np->getMoney());

                $note = '充值了' . $gxOrder->getAmount() . '元';
                $this->userWalletService->deposit($wallet->getId(), $gxOrder->getAmount() * 100, $note);

                $note = '购买VIP' . $gxOrder->getVipItemId() . '支出了' . ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) . '元';
                $this->userWalletService->withdraw($wallet->getId(), ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) * 100, $note);


                $this->gxOrderService->flush($gxOrder);
                $this->gxOrderService->getEntityManager()->commit();
            } catch (Exception $exception) {
                $this->gxOrderService->getEntityManager()->rollback();
                $this->logger->error('[支付回调] 更新订单信息失败' . $exception->getMessage() . ($rawData));
                return '更新订单信息失败' . $exception->getMessage();
            }
            $ret = $this->paySuccess($gxOrder);
            if ($ret->isFail()) {
                // 只记录这个
                $this->logger->error('[支付回调] 处理订单失败' . $ret->getMsg() . ($rawData));
            }
            return new Response('succeed');
        } catch (Exception $e) {
            $this->logger->error('[ALIPAY NOTIFY ERROR] = ' . $e->getMessage() . json_encode($request->request->all()));
            return 'error' . $e->getMessage();
        }
    }

    /**
     * @param GxOrder $gxOrder
     * @return CallResult
     * @throws Exception
     */
    protected function paySuccess(GxOrder $gxOrder)
    {
        $this->gxConfig->init($gxOrder->getProjectId());
        // 完成支付后依据订单类型进行分类处理
        if ($gxOrder->getVipItemId() === 1) {
            // vip1
            $ret = $this->profitGraphService->upgradeToVip1($gxOrder->getId(), $gxOrder->getUid(), $this->gxConfig);
        } else {
            // vip2 - vip9
            $ret = $this->profitGraphService->upgradeToVipN($gxOrder->getId(), $gxOrder->getUid());
        }
        return $ret;
    }
}
