<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\PayOrder;
use App\Helper\DesHelper;
use App\Message\PaySuccessMsg;
use App\ServiceInterface\AlipayServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use by\component\proxyPay\Exceptions\InvalidConfigException;
use by\component\proxyPay\Exceptions\InvalidSignException;
use by\component\proxyPay\Supports\Collection;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlipayController extends AbstractController
{
    protected $alipayService;
    /**
     * @var LoggerInterface
     */
    private $logger;

    protected $payOrderService;

    /**
     * AlipayController constructor.
     * @param PayOrderServiceInterface $payOrderService
     * @param AlipayServiceInterface $alipayService
     * @param LoggerInterface $logger
     */
    public function __construct(PayOrderServiceInterface $payOrderService,
                                AlipayServiceInterface $alipayService, LoggerInterface $logger)
    {
        $this->alipayService = $alipayService;
        $this->payOrderService = $payOrderService;
        $this->logger = $logger;
    }

    /**
     * @Route("/alipay_query_{payCode}", name="alipay_query")
     * @param $payCode
     * @return Collection
     */
    public function query($payCode)
    {
        return $this->alipayService->query($payCode);
    }

    /**
     * @Route("/alipay_pc_{payInfo}", name="alipay_pc", methods={"GET","POST"})
     * @param $payInfo
     * @return array|string
     */
    public function index($payInfo)
    {
        $payInfo = DesHelper::decode(urldecode($payInfo));

        $payInfo = json_decode(base64_decode($payInfo), JSON_OBJECT_AS_ARRAY);
        if (!is_array($payInfo)) {
            return $this->render('alipay/error.html.twig', ['msg' => '参数验证失败']);
        }
        if (count($payInfo) !== 3) {
            return $this->render('alipay/error.html.twig', ['msg' => '数据错误']);
        }
        $payCode = $payInfo['pay_code'];
        $subject = $payInfo['subject'];
        $money = $payInfo['money'];

        $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);
        if (!($payOrder instanceof PayOrder)) {
            return $this->render('alipay/error.html.twig', ['msg' => '交易号非法']);
        }

        if ($payOrder->getMoney() != intval($money)) {
            return $this->render('alipay/error.html.twig', ['msg' => '订单金额不匹配']);
        }

        $payMoney = StringHelper::numberFormat($payOrder->getMoney() / 100);
        $order = [
            'out_trade_no' => $payCode,
            'total_amount' => strval($payMoney),
            'subject' => $subject,
        ];
        return $this->alipayService->web($order);
    }


    /**
     * @Route("/alipay_wap_{payInfo}", name="alipay_wap", methods={"GET","POST"})
     * @param $payInfo
     * @return array|string
     */
    public function wap($payInfo)
    {
        $payInfo = DesHelper::decode(urldecode($payInfo));

        $payInfo = json_decode(base64_decode($payInfo), JSON_OBJECT_AS_ARRAY);
        if (!is_array($payInfo)) {
            return $this->render('alipay/error.html.twig', ['msg' => '参数验证失败']);
        }
        if (count($payInfo) !== 3) {
            return $this->render('alipay/error.html.twig', ['msg' => '数据错误']);
        }
        $payCode = $payInfo['pay_code'];
        $subject = $payInfo['subject'];
        $money = $payInfo['money'];

        $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);
        if (!($payOrder instanceof PayOrder)) {
            return $this->render('alipay/error.html.twig', ['msg' => '交易号非法']);
        }

        if ($payOrder->getMoney() != intval($money)) {
            return $this->render('alipay/error.html.twig', ['msg' => '订单金额不匹配']);
        }

        $payMoney = StringHelper::numberFormat($payOrder->getMoney() / 100);
        $order = [
            'out_trade_no' => $payCode,
            'total_amount' => strval($payMoney),
            'subject' => $subject,
        ];
        return $this->alipayService->wap($order);
    }

    /**
     * @Route("/alipay/show", name="alipay_show", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws InvalidConfigException
     * @throws InvalidSignException
     */
    public function show(Request $request)
    {
        $this->alipayService->verify();
        $outTradeNo = $request->get('out_trade_no', '');
        $totalAmount = $request->get('total_amount', 0);
        $tradeNo = $request->get('trade_no', '');
        $payOrder = $this->payOrderService->info(['pay_code' => $outTradeNo]);
        if (!($payOrder instanceof PayOrder)) {
            return $this->render("alipay/error.html.twig", [
                'msg' => '交易单号不存在',
            ]);
        }
        $params = [
            'pay_code' => $outTradeNo,
            'money' => intval(100 * $totalAmount),
            'trade_no' => $tradeNo,
            'order_no' => $payOrder->getOutOrderNo(),
            'client_id' => $payOrder->getClientId()
        ];

        if (!empty($payOrder->getReturnUrl())) {
            $url = urldecode($payOrder->getReturnUrl());
            $url = $this->getSchemeHost($url);
            $url .= '?' . http_build_query($params);
            return $this->redirect($url);
        }

        return $this->render("alipay/show.html.twig", $params);
    }

    function getSchemeHost($url)
    {
        $parseUrl = parse_url($url);
        $sh = '';
        if (array_key_exists('scheme', $parseUrl)) {
            $sh .= $parseUrl['scheme'] . '://';
        }
        if (array_key_exists('host', $parseUrl)) {
            $sh .= $parseUrl['host'];
        }
        if (array_key_exists('port', $parseUrl)) {
            $sh .= ':' . $parseUrl['port'];
        }
        return $sh;
    }

    /**
     * @Route("/alipay/notify", name="alipay_notify", methods={"GET","POST"})
     */
    public function notify()
    {

        try {
            $input = file_get_contents('php://input');
            $this->logger->debug('支付回调信息1' . json_encode($input));

            $data = $this->alipayService->verify(); // 是的，验签就这么简单！

            $this->logger->debug('支付回调信息2' . json_encode($data));

            $tradeStatus = $data->get('trade_status');

            if ($tradeStatus == 'TRADE_SUCCESS') {
                $result = $this->paySuccess($data);
            } elseif ($tradeStatus == 'TRADE_FINISHED') {
                $result = $this->paySuccess($data);
            } elseif ($tradeStatus == 'WAIT_BUYER_PAY') {
                return $this->alipayService->success();
            } else {
                $this->logger->error('[ALIPAY]unknown trade status' . $tradeStatus);
                return 'unknown trade status';
            }

            if ($result->isFail() && $result->getCode() != -3) {
                $this->logger->error('[ALIPAY]' . $result->getMsg());
                return $result->getMsg();
            }
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
        } catch (Exception $e) {
            $all = Request::createFromGlobals()->request->all();
            $all = json_encode($all);
            $this->logger->error('[ALIPAY NOTIFY ERROR] = ' . $e->getMessage() . $all);
            return 'error' . $e->getMessage();
        }

        return $this->alipayService->success();
    }

    protected function paySuccess(Collection $data)
    {

        /*"gmt_create":"2019-07-19 19:08:38"
"charset":"GBK"
"gmt_payment":"2019-07-19 19:08:51"
"notify_time":"2019-07-19 19:08:53"
"subject":"\u652f\u4ed8\u6d4b\u8bd5"
"sign":"kLeiVhPqZWkNqhEvCzzyn+WiJVvDOvXkfPe6v2V8e5aEXXt5Tzn7VWrJoMydFKvEn57DE0HkUt0GrgqySEO0QRsQZmsLAO8TVdoPc7n4GxY5T6s7amAJxJClETIppdNgYBU5rIN0kktY8YskmLeDkHrNT\/dsghyrwjotnUyWElLYHrtmz\/bX3tk4e2njxHYpDAxIQoW\/WlzA\/6yobXFvqHoO15thlhYUhjbmrpVaIX8ugO5STBn2Od7nW7mlEei3OjCgt3JQmkgWOIy8nAsFsI8deetJ3V2ZCNsd+Ja+by4YNodpJ5z\/qudfv64LARM3D212kcH3o42iGNClzP6WYw=="
"buyer_id":"2088102179080383"
"invoice_amount":"100.00"
"version":"1.0"
"notify_id":"2019071900222190852080381000444454"
"fund_bill_list":"[{\"amount\":\"100.00\"\"fundChannel\":\"ALIPAYACCOUNT\"}]"
"notify_type":"trade_status_sync"
"out_trade_no":"61408015635339962C64AC8A680308"
"total_amount":"100.00"
"trade_status":"TRADE_SUCCESS"
"trade_no":"2019071922001480381000151591"
"auth_app_id":"2016100100642501"
"receipt_amount":"100.00"
"point_amount":"0.00"
"buyer_pay_amount":"100.00"
app_id:2016100100642501
"sign_type":"RSA2"
"seller_id":"2088102178162820"
        */
        $payCode = $data->get('out_trade_no', '');
        $tradeNo = $data->get('trade_no', '');
        $tradeStatus = $data->get('trade_status', '');
        $sellerId = $data->get('seller_id', '');
        $appId = $data->get('app_id', '');
        $totalAmount = $data->get('total_amount', 0);
        $fundBillList = $data->get('fund_bill_list', '');
        //
        //receipt_amount、invoice_amount、buyer_pay_amount、point_amount、voucher_detail_list 等参数在用户使用优惠券时才会返回，其他情况请不要使用。
        // 商户实际收款金额
        $receiptAmount = $data->get('receipt_amount', 0);
        // 集分宝
        $pointAmount = $data->get('point_amount', 0);
        // 买家支付金额
        $buyerPayAmount = $data->get('buyer_pay_amount', 0);

        $payTime = $data->get('gmt_payment', 0);
        $payTime = strtotime(urldecode($payTime)) - 8 * 3600;
        $notifyTime = $data->get('notify_time', 0);
        $notifyTime = strtotime(urldecode($notifyTime)) - 8 * 3600;
        $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);

        // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
        if (!($payOrder instanceof PayOrder)) {
            return CallResultHelper::fail($payCode . ' out_trade_no not exist');
        }

        // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额
        $totalAmount = intval($totalAmount * 100);

        if ($totalAmount != $payOrder->getMoney()) {
            return CallResultHelper::fail('payCode = ' . $payCode . ' notify money ' . $totalAmount . ' not equal ' . $payOrder->getMoney());
        }
        // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
//
        // 4、验证app_id是否为该商户本身。
        if ($appId != $this->alipayService->getConfig('app_id')) {
            return CallResultHelper::fail($appId . ' app id not same');
        }
        $payload = [
            'seller_id' => $sellerId,
            'fundBillList' => $fundBillList,
            'receiptAmount' => $receiptAmount,
            'pointAmount' => $pointAmount,
            'buyerPayAmount' => $buyerPayAmount
        ];

        // 5、其它业务逻辑情况
        $ret = $this->payOrderService->setPaySuccess($payCode, $tradeNo, $tradeStatus, $payTime, $notifyTime, $totalAmount, $payload);

        if ($ret instanceof CallResult && ($ret->isSuccess() || $ret->getCode() == -3)) {
            // 发送交易成功消息
            $msg = new PaySuccessMsg();
            $msg->setSubject($payOrder->getSubject());
            $msg->setNote($payOrder->getNote());
            $msg->setPayTime($payTime);
            $msg->setPayCode($payOrder->getPayCode());
            $msg->setOutOrderNo($payOrder->getOutOrderNo());
            $msg->setTotalAmount($totalAmount);
            $this->dispatchMessage($msg);
        }
        return $ret;
    }

}
