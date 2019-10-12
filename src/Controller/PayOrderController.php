<?php


namespace App\Controller;


use App\Entity\PayOrder;
use App\Helper\DesHelper;
use App\ServiceInterface\AlipayServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class PayOrderController extends BaseSymfonyApiController
{
    protected $payOrderService;
    protected $alipayService;

    public function __construct(
        AlipayServiceInterface $alipayService,
        PayOrderServiceInterface $payOrderService,
        KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->alipayService = $alipayService;
        $this->payOrderService = $payOrderService;
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

    public function info($orderNo)
    {
        $payOrder = $this->payOrderService->info(['client_id' => $this->getClientId(), 'out_order_no' => $orderNo]);
        if (!$payOrder instanceof PayOrder) {
            return CallResultHelper::fail('支付单不存在');
        }

        return $payOrder;
    }

    public function payInfo($orderNo)
    {
        $payOrder = $this->payOrderService->info(['client_id' => $this->getClientId(), 'out_order_no' => $orderNo]);
        if (!$payOrder instanceof PayOrder) {
            return CallResultHelper::fail('支付单不存在');
        }

        $collection = $this->alipayService->query($payOrder->getPayCode());
        if ($collection->get('code', '') == '10000') {
            $data = [
                'pay_code' => $collection->get('out_trade_no', ''),
                'trade_status' => $collection->get('trade_status', ''),
                'total_amount' => $collection->get('total_amount', '0'),
                'trade_no' => $collection->get('trade_no', ''),
                'buyer_logon_id' => $collection->get('buyer_logon_id', '')
            ];
            return CallResultHelper::success($data);
        }
        return CallResultHelper::fail($collection->get('msg', ''));
    }

}
