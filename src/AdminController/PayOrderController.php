<?php


namespace App\AdminController;


use App\Entity\PayOrder;
use App\Helper\DesHelper;
use App\ServiceInterface\AlipayServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class PayOrderController extends BaseNeedLoginController
{
    protected $payOrderService;
    protected $alipayService;

    public function __construct(
        PayOrderServiceInterface $payOrderService, AlipayServiceInterface $alipayService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->payOrderService = $payOrderService;
        $this->alipayService = $alipayService;
    }

    /**
     * alipay pc 端支付
     * @param $subject
     * @param $orderNo
     * @param $money
     * @param $callbackUrl
     * @param string $returnUrl
     * @param string $note
     * @return \by\infrastructure\base\CallResult
     */
    public function aliPayPc($subject, $orderNo, $money, $callbackUrl, $returnUrl = '', $note = '')
    {
        $ret = $this->payOrderService->create($subject, $this->getClientId(), $orderNo, $money, PayOrder::PayTypeOfAliPayPc, $callbackUrl, $returnUrl, $note);
        if ($ret->isFail()) {
            return $ret;
        }
        $data = $ret->getData();
        if (!$data instanceof PayOrder) {
            return CallResultHelper::fail('创建失败');
        }

        $payInfo = json_encode(['pay_code' => $data->getPayCode(), 'subject' => $data->getSubject(), 'money' => $data->getMoney()], JSON_UNESCAPED_UNICODE);
        $payInfo = DesHelper::encode(base64_encode($payInfo));

        $payUrl = $this->request->getSchemeAndHttpHost() . $this->generateUrl('alipay_pc', ['payInfo' => urlencode($payInfo)]);

        $payUrl = urlencode($payUrl);

        return CallResultHelper::success($payUrl);
    }

    /**
     * alipay 手机网站支付
     * @param $subject
     * @param $orderNo
     * @param $money
     * @param $callbackUrl
     * @param string $returnUrl
     * @param string $note
     * @return \by\infrastructure\base\CallResult
     */
    public function aliPayWap($subject, $orderNo, $money, $callbackUrl, $returnUrl = '', $note = '')
    {
        $ret = $this->payOrderService->create($subject, $this->getClientId(), $orderNo, $money, PayOrder::PayTypeOfAliPayWap, $callbackUrl, $returnUrl, $note);
        if ($ret->isFail()) {
            return $ret;
        }
        $data = $ret->getData();
        if (!$data instanceof PayOrder) {
            return CallResultHelper::fail('创建失败');
        }
        $payInfo = json_encode(['pay_code' => $data->getPayCode(), 'subject' => $data->getSubject(), 'money' => $data->getMoney()], JSON_UNESCAPED_UNICODE);
        $payInfo = DesHelper::encode(base64_encode($payInfo));

        $payUrl = $this->request->getSchemeAndHttpHost() . $this->generateUrl('alipay_wap', ['payInfo' => urlencode($payInfo)]);

        $payUrl = urlencode($payUrl);

        return CallResultHelper::success($payUrl);
    }

    /**
     * 手动通知
     * @param $id
     * @return \by\infrastructure\base\CallResult
     */
    public function notify($id)
    {
        $payOrder = $this->payOrderService->info(['id' => $id]);
        if (!$payOrder instanceof PayOrder) {
            return CallResultHelper::fail('id 错误');
        }

        return $this->payOrderService->notify($payOrder);
    }

    public function info($orderNo) {
        $payOrder = $this->payOrderService->info(['client_id' => $this->getClientId(), 'order_no' => $orderNo]);
        if (!$payOrder instanceof PayOrder) {
            return CallResultHelper::fail('支付单不存在');
        }

        return $payOrder;
    }

    /**
     * @param $payCode
     * @param $amount
     * @param string $reason
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function refund($payCode, $amount, $reason = '退款') {

        $this->checkLogin();

        $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);
        if (!($payOrder instanceof PayOrder)) {
            return CallResultHelper::fail('invalid pay_code');
        }
        $tradeNo = $payOrder->getTradeNo();

        if (empty($tradeNo)) {
            return CallResultHelper::fail('trade no is empty');
        }

        if ($payOrder->getNotifyMoney()  < $amount) {
            $amount = StringHelper::numberFormat($payOrder->getNotifyMoney() / 100);
        }

        $refundInfo = [
            'out_trade_no' => $payCode,
            'trade_no' => $tradeNo,
            'refund_amount' => $amount,
            'refund_reason' => $reason,
            'operator_id' => $this->getUid(),
        ];
        $result = $this->alipayService->refund($refundInfo);

        $code = $result->get('code', '');
        if ($code == '10000') {
            $payload = [
                'gmt_refund_pay' => $result->get('gmt_refund_pay', ''),
                'buyer_logon_id' => $result->get('buyer_logon_id', ''),
                'buyer_user_id' => $result->get('buyer_user_id', ''),
                'msg' => $result->get('msg', ''),
                'refund_fee' => $result->get('refund_fee', ''),
                'send_back_fee' => $result->get('send_back_fee', ''),
            ];
            $payOrder->setRefundStatus(1);
            $payOrder->setRefundPayload(json_encode($payload));
            $this->payOrderService->flush($payOrder);
            return CallResultHelper::success($result);
        } else {
            return CallResultHelper::fail('fail', $result);
        }
    }

}
