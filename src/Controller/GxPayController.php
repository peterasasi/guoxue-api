<?php


namespace App\Controller;


use App\Common\CacheKeys;
use App\Common\GxGlobalConfig;
use App\Common\PayWayConst;
use App\Entity\Config;
use App\Entity\GxOrder;
use App\Entity\UserWallet;
use App\Entity\XftMerchant;
use App\ServiceInterface\ConfigServiceInterface;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\PlatformWalletServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use App\ServiceInterface\XftMerchantServiceInterface;
use by\component\pay841\Pay841;
use by\component\usdt_pay\UsdtPay;
use by\component\xft_pay\XftPay;
use by\component\yipay\YiPay;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use Dbh\SfCoreBundle\Common\ByCacheKeys;
use Dbh\SfCoreBundle\Common\ByEnv;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GxPayController extends AbstractController
{
    protected $logger;
    protected $gxOrderService;
    protected $gxConfig;
    protected $profitGraphService;
    protected $platformWalletService;
    protected $userWalletService;
    protected $configService;
    protected $xftMerchantService;
    protected $xftCfgId;
    protected $cacheItemPool;

    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        XftMerchantServiceInterface $xftMerchantService,
        ConfigServiceInterface $configService,
        UserWalletServiceInterface $userWalletService,
        PlatformWalletServiceInterface $platformWalletService,
        ProfitGraphServiceInterface $profitGraphService,
        GxGlobalConfig $gxGlobalConfig,
        GxOrderServiceInterface $gxOrderService, LoggerInterface $logger)
    {
        $this->xftCfgId = 0;
        $this->cacheItemPool = $cacheItemPool;
        $this->xftMerchantService = $xftMerchantService;
        $this->configService = $configService;
        $this->userWalletService = $userWalletService;
        $this->platformWalletService = $platformWalletService;
        $this->logger = $logger;
        $this->profitGraphService = $profitGraphService;
        $this->gxConfig = $gxGlobalConfig;
        $this->gxOrderService = $gxOrderService;
    }

    /**
     * @param $projectId
     * @return int
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getPayWay($projectId)
    {
        $item = $this->cacheItemPool->getItem(CacheKeys::GxPayWay);
        if ($item->isHit()) {
            $payWay = intval($item->get());
        } else {
            $payWay = -1;
            $cfg = $this->configService->info(['name' => 'payway', 'project_id' => $projectId]);
            if ($cfg instanceof Config) {
                $payWay = intval($cfg->getValue());
                $item->set($payWay);
                $item->expiresAfter(CacheKeys::getExpireTime(CacheKeys::GxPayWay));
                $this->cacheItemPool->save($item);
            }
        }
        return $payWay;
    }

    /**
     * @Route("/fake-upgradev1-{orderId}", name="pay1_fake_upgrade_v1", methods={"GET","POST"})
     * @param $orderId
     * @return CallResult|string
     * @throws Exception
     */
    public function test($orderId)
    {
        return $this->render('gxpay/show.html.twig', ['url' => ByEnv::get('H5_ENTRY'), 'total_amount' => 1100, 'order_no' => '33']);
//        $gxOrder = $this->gxOrderService->info(['id' => $orderId]);
//        if (!$gxOrder instanceof GxOrder) return 'invalid order id';
//        $this->gxConfig->init($gxOrder->getProjectId());
//        $ret = $this->profitGraphService->upgradeToVip1($orderId, $gxOrder->getUid(), $this->gxConfig);
//        $ret = $this->profitGraphService->upgradeToVipN($orderId, $gxOrder->getUid());
//        $ret = $this->profitGraphService->getParentsUid(1, 8, '6,9,10,');
//        var_dump($ret);
//        exit;
//        return CallResultHelper::success($ret);
    }

    protected function paySuccess(GxOrder $gxOrder)
    {
        $this->gxConfig->init($gxOrder->getProjectId());
        // 完成支付后依据订单类型进行分类处理
        if ($gxOrder->getVipItemId() === 1) {
            // vip1
            $ret = $this->profitGraphService->upgradeToVip1($gxOrder->getId(), $gxOrder->getUid(), $this->gxConfig);
        } else {
            // vip2 - vip10
            $ret = $this->profitGraphService->upgradeToVipN($gxOrder->getId(), $gxOrder->getUid());
        }
        return $ret;
    }

    /**
     * 支付方式1 - 模拟支付成功
     * @Route("/fake-success-{orderNo}", name="pay1_fake", methods={"GET","POST"})
     * @param $orderNo
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function fake($orderNo)
    {
        $fakePay = ByEnv::get('USDT_FAKE_PAY');
        if ($fakePay == 0) {
            return $this->render('gxpay/error.html.twig', ['msg' => '不支持']);
        }
        $gxOrder = $this->gxOrderService->info(['order_no' => $orderNo]);
        if (!$gxOrder instanceof GxOrder) {
            return $this->render('gxpay/error.html.twig', ['msg' => '订单号非法']);
        }

        $wallet = $this->userWalletService->info(['uid' => $gxOrder->getUid()]);
        if (!$wallet instanceof UserWallet) {
            return $this->render('gxpay/error.html.twig', ['msg' => '用户' . $gxOrder->getUid() . '的钱包不存在']);
        }
        $this->gxOrderService->getEntityManager()->beginTransaction();
        try {
            $this->gxOrderService->findById($gxOrder->getId(), LockMode::PESSIMISTIC_READ);
            $gxOrder->setPayStatus(GxOrder::Paid);
            $gxOrder->setPaidTime(time());
            $gxOrder->setArrivalAmount($gxOrder->getAmount());
            $gxOrder->setRemark($gxOrder->getRemark() . '[模拟订单]');
            $gxOrder->setPw(PayWayConst::PW_FAKE);

            $note = '充值了' . $gxOrder->getAmount() . '元';
            $this->userWalletService->deposit($wallet->getId(), $gxOrder->getAmount() * 100, $note);

            $note = '购买VIP' . $gxOrder->getVipItemId() . '支出了' . ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) . '元';
            $this->userWalletService->withdraw($wallet->getId(), (($gxOrder->getAmount() - $gxOrder->getExtraAmount())) * 100, $note);

            $this->gxOrderService->flush($gxOrder);
            $this->gxOrderService->getEntityManager()->commit();
        } catch (Exception $exception) {
            $this->gxOrderService->getEntityManager()->rollback();
            return $this->render('gxpay/error.html.twig', ['msg' => '更新订单信息失败' . $exception->getMessage()]);
        }
        $ret = $this->paySuccess($gxOrder);
        if ($ret->isFail()) {
            return $this->render('gxpay/error.html.twig', ['msg' => '处理订单失败' . $ret->getMsg()]);
        }

        $url = empty($gxOrder->getShowJumpUrl()) ? ByEnv::get('H5_ENTRY') : $gxOrder->getShowJumpUrl();
        return $this->render('gxpay/show.html.twig', ['url' => $url, 'total_amount' => $gxOrder->getArrivalAmount(), 'order_no' => $orderNo]);
    }

    /**
     *
     * @Route("/pay1/show", name="pay1_show", methods={"GET","POST"})
     * @return Response
     */
    public function show()
    {
        return $this->render('gxpay/show.html.twig', ['url' => ByEnv::get('H5_ENTRY'), 'total_amount' => '', 'order_no' => '']);
    }

    protected function getXftConfig()
    {

//        $item = $this->cacheItemPool->getItem(CacheKeys::GxXftPayConfig);
//        if  ($item->isHit()) {
//            $cfg = @json_decode($item->get(), JSON_OBJECT_AS_ARRAY);
//        } else {
        $list = $this->xftMerchantService->queryAllBy(['enable' => StatusEnum::ENABLE], ['id' => 'desc']);
        if (!is_array($list) || count($list) == 0) {
            return null;
        }
        $validCnt = count($list);
        $r = rand(0, $validCnt - 1);
        $cfg = $list[$r];
//            $item->set(json_encode($cfg));
//            $item->expiresAfter(CacheKeys::getExpireTime(CacheKeys::GxXftPayConfig));
//            $this->cacheItemPool->save($item);
//        }

        $xftPay = new XftPay();
        $xftPay->getConfig()->setAppId($cfg['app_id']);
        $xftPay->getConfig()->setMerchantCode($cfg['code']);
        $xftPay->getConfig()->setKey($cfg['md5key']);
        $xftPay->getConfig()->setNotifyUrl($cfg['notify_url']);
        $xftPay->getConfig()->setClientIp($cfg['client_ip']);
        $this->xftCfgId = $cfg['id'];

        return $xftPay;
    }

    /**
     * 支付方式1 - 发起支付
     * @Route("/pay1/{orderNo}", name="pay1_start", methods={"GET","POST"})
     * @param Request $request
     * @param $orderNo
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function pay(Request $request, $orderNo)
    {

        $gxOrder = $this->gxOrderService->info(['order_no' => $orderNo]);
        if (!$gxOrder instanceof GxOrder) {
            return $this->render('gxpay/error.html.twig', ['msg' => '订单号非法']);
        }

        $fakePay = $this->getPayWay($gxOrder->getProjectId());
        if ($fakePay == 6) {
            $amount = $gxOrder->getAmount();
            $notifyUrl = ByEnv::get("YIPAY_NOTIFY_URL");
            $ret = (new YiPay())->alipay($gxOrder->getOrderNo(), $amount, $notifyUrl);
            if ($ret->isFail()) {
                return $this->render('gxpay/error.html.twig', ['msg' => $ret->getMsg()]);
            }
            return new RedirectResponse($ret->getData());
        } else if ($fakePay == 4) {
            $amount = $gxOrder->getAmount();
            $pay841 = new Pay841();
            $notifyUrl = ByEnv::get('PAY841_NOTIFY_URL');
            $returnUrl = ByEnv::get('PAY841_RETURN_URL');
            $ret = $pay841->alipay($gxOrder->getOrderNo(), $amount, $notifyUrl, $returnUrl);
            if ($ret->isFail()) {
                return $this->render('gxpay/error.html.twig', ['msg' => $ret->getMsg()]);
            }
            return new RedirectResponse($ret->getData());
        } else if ($fakePay == 2) {
            $amount = $gxOrder->getAmount();
            $xftPay = $this->getXftConfig();
            if (is_null($xftPay)) {
                return $this->render('gxpay/error.html.twig', ['msg' => '支付通道未配置']);
            }
            $ret = $xftPay->getPayUrl($gxOrder->getOrderNo(), intval($amount * 100), 'VIPITEM' . $gxOrder->getVipItemId(), 'VIPITEM' . $gxOrder->getVipItemId(), 'VIPITEM' . $gxOrder->getVipItemId());
            if ($ret->isFail()) {
                $this->xftMerchantService->incFailCnt($this->xftCfgId);
                return $this->render('gxpay/error.html.twig', ['msg' => $ret->getMsg()]);
            }
            $payConfig = [
                'code' => $xftPay->getConfig()->getMerchantCode(),
                'client_ip' => $xftPay->getConfig()->getClientIp(),
                'notify_url' => $xftPay->getConfig()->getNotifyUrl(),
                'key' => $xftPay->getConfig()->getKey(),
                'app_id' => $xftPay->getConfig()->getKey()
            ];
            $gxOrder->setPayConfig(json_encode($payConfig));
            $this->gxOrderService->flush($gxOrder);
            $this->xftMerchantService->incSucCnt($this->xftCfgId);
            return new RedirectResponse($ret->getData());
        } else if ($fakePay == 0) {
            $amount = $gxOrder->getAmount();
            $url = (new UsdtPay())->getPayUrl($gxOrder->getOrderNo(), $amount, 2);
            return new RedirectResponse($url);
        } else if ($fakePay == 1) {
            $payUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('pay1_fake', ['orderNo' => $orderNo]);
            return $this->render('gxpay/fake.html.twig', ['pay_url' => $payUrl]);
        } else {
            return $this->render('gxpay/error.html.twig', ['msg' => '支付通道未配置']);
        }
    }


    /**
     * 支付方式1 - 异步回调
     * @Route("/pay1/notify", name="pay1_notify", methods={"GET","POST"})
     * @param Request $request
     * @return string
     */
    public function notify(Request $request)
    {
        try {
            $customerAmount = $request->get('customerAmount', 0);
            $customerAmountCny = $request->get('customerAmountCny', 0);
            $outOrderId = $request->get('outOrderId', '');
            $orderId = $request->get('orderId', '');
            $sign = $request->get('sign', '');
            $this->logger->debug('支付回调信息1' . json_encode($request->request->all()));

            $signVerifyOpen = ByEnv::get('SIGN_VERIFY_OPEN');
            if (!empty($signVerifyOpen) && $signVerifyOpen == 1) {
                $verifyRet = (new UsdtPay())->cbVerifySign($outOrderId, $orderId, $customerAmount, $customerAmountCny, $sign);

                if (!$verifyRet) {
                    $this->logger->error('[支付回调签名失败]');
                    return 'verify sign fail';
                }
            }

            $gxOrder = $this->gxOrderService->info(['order_no' => $outOrderId]);
            if (!$gxOrder instanceof GxOrder) {
                $this->logger->error('[订单号不存在]');
                return 'out_order_id not exists';
            }

            $wallet = $this->userWalletService->info(['uid' => $gxOrder->getUid()]);
            if (!$wallet instanceof UserWallet) {
                $this->logger->error('用户' . $gxOrder->getUid() . '的钱包不存在');
                return '用户' . $gxOrder->getUid() . '的钱包不存在';
            }

            $this->gxOrderService->getEntityManager()->beginTransaction();
            try {
                $this->gxOrderService->findById($gxOrder->getId(), LockMode::PESSIMISTIC_READ);
                $gxOrder->setPayStatus(GxOrder::Paid);
                $gxOrder->setPaidTime(time());
                $gxOrder->setArrivalAmount($customerAmountCny);
                $gxOrder->setSign($sign);
                $gxOrder->setPayRetOrderId($orderId);
                $gxOrder->setPw(PayWayConst::PW001);

                $note = '充值了' . $gxOrder->getAmount() . '元';
                $this->userWalletService->deposit($wallet->getId(), $gxOrder->getAmount() * 100, $note);

                $note = '购买VIP' . $gxOrder->getVipItemId() . '支出了' . $gxOrder->getAmount() . '元';
                $this->userWalletService->withdraw($wallet->getId(), $gxOrder->getAmount() * 100, $note);

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
            return (new UsdtPay())->getSuccessStr();
        } catch (Exception $e) {
            $this->logger->error('[ALIPAY NOTIFY ERROR] = ' . $e->getMessage() . json_encode($request->request->all()));
            return 'error' . $e->getMessage();
        }
    }
}
