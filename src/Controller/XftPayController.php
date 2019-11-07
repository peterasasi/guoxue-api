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
use by\component\string_extend\helper\StringHelper;
use by\component\usdt_pay\UsdtPay;
use by\component\xft_pay\NotifyParams;
use by\component\xft_pay\SignTool;
use by\component\xft_pay\XftPay;
use by\infrastructure\base\CallResult;
use Dbh\SfCoreBundle\Common\ByEnv;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class XftPayController extends AbstractController
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
     * @Route("/pay/notify/xft", name="pay_notify_xft", methods={"GET","POST"})
     * @param Request $request
     * @return string
     */
    public function notify(Request $request)
    {
        try {

//            out_trade_no	是	String	商户订单号，商户自行生成的唯一订单标识
//created	是	String	订单生成时间
//third_trade_no	是	String	第三方流水号
//state	是	String	订单状态
//merchant_code	是	String	商户编号
//update_time	是	String	订单支付时间
//amount	是	int	订单金额,单位:分
//store_code	是	String	门店编号
//operator_id	是	String	操作员 id
//product	是	String	支付产品,详细参考 支付产品 product 属性值
//client_ip	是	String	客户端调用的 IP
//subject	是	String	订单标题
//body	是	String	商品描述信息
//description	是	String	订单附加说明
//payer_id	否	String	付款人标识
//sign_type	是	String	签名类型
//sign	是	String	签名值
            $rawData = $request->getContent();
            if (is_string($rawData)) $rawData = json_decode($rawData, JSON_OBJECT_AS_ARRAY);
            if (empty($rawData)) return 'error notify data';

            try {
                $np = new NotifyParams($rawData);
            } catch (Exception $exception) {
                return 'notify params invalid';
            }

            $payInstance = new XftPay();
            $signVerifyOpen = ByEnv::get('SIGN_VERIFY_OPEN');
            if (!empty($signVerifyOpen) && $signVerifyOpen == 1) {
                $all = $rawData;
                unset($all['sign']);
                $localSign = SignTool::sign($all, $payInstance->getConfig());
                if (!($localSign === $np->getSign())) {
                    $this->logger->error('[支付回调签名失败]');
                    return 'verify sign fail';
                }
            }

            $gxOrder = $this->gxOrderService->info(['order_no' => $np->getOutTradeNo()]);
            if (!$gxOrder instanceof GxOrder) {
                $this->logger->error('[订单号不存在]');
                return 'out_order_id not exists';
            }

            if ($gxOrder->getPayStatus() != GxOrder::PayInitial) {
                $this->logger->error('[支付回调] 已处理订单' . $gxOrder->getOrderNo());
                return 'already processed';
            }

            if ($np->getState() != '00') {
                // 订单未支付成功 记录订单状态到异常
                $gxOrder->setExceptionMsg($gxOrder->getExceptionMsg() . '[state]' . $np->getState());
                $this->gxOrderService->flush($gxOrder);
                return 'order failed';
            }

            $wallet = $this->userWalletService->info(['uid' => $gxOrder->getUid()]);
            if (!$wallet instanceof UserWallet) {
                $this->logger->error('用户' . $gxOrder->getUid() . '的钱包不存在');
                return '用户' . $gxOrder->getUid() . '的钱包不存在';
            }

            $this->gxOrderService->getEntityManager()->beginTransaction();
            try {
                $this->gxOrderService->findById($gxOrder->getId(), LockMode::PESSIMISTIC_READ);
                if ($gxOrder->getPayStatus() == GxOrder::Paid) {
                    $this->gxOrderService->getEntityManager()->rollback();
                    $this->logger->error('[支付回调] 订单已处理' . $gxOrder->getOrderNo());
                    return 'already processed';
                }
                $gxOrder->setPayStatus(GxOrder::Paid);
                $gxOrder->setPaidTime(time());
                $gxOrder->setArrivalAmount(StringHelper::numberFormat($np->getAmount() / 100, 2));
                $gxOrder->setSign($np->getSign());
                $gxOrder->setPayRetOrderId($np->getThirdTradeNo());
                $gxOrder->setMerchantCode($np->getMerchantCode());
                $gxOrder->setPw(PayWayConst::PW002);

                $note = '充值了' . $gxOrder->getAmount() . '元';
                $this->userWalletService->deposit($wallet->getId(), $gxOrder->getAmount() * 100, $note);

                $note = '购买VIP' . $gxOrder->getVipItemId() . '支出了' . ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) . '元';
                $this->userWalletService->withdraw($wallet->getId(), ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) * 100, $note);


                $this->gxOrderService->flush($gxOrder);
                $this->gxOrderService->getEntityManager()->commit();
            } catch (Exception $exception) {
                $this->gxOrderService->getEntityManager()->rollback();
                $this->logger->error('[支付回调] 更新订单信息失败' . $exception->getMessage());
                return '更新订单信息失败' . $exception->getMessage();
            }
            $ret = $this->paySuccess($gxOrder);
            if ($ret->isFail()) {
                // 只记录这个
                $this->logger->error('[支付回调] 处理订单失败' . $ret->getMsg());
            }
            return $payInstance->getSuccessStr();
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
