<?php


namespace by\component\xft_pay;


use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\ByEnv;

class XftPay
{
    const Gateway1 = 'https://xlink.91xft.cn/transaction/unify/charge/pay';

    protected $config;

    public function __construct()
    {
        $this->config = new XftPayConfig();
        $this->config->setClientIp(ByEnv::get('XFT_PAY_CLIENT_IP'));
        $this->config->setAppId(ByEnv::get('XFT_PAY_APP_ID'));
        $this->config->setMerchantCode(ByEnv::get('XFT_PAY_M_CODE'));
        $this->config->setKey(ByEnv::get('XFT_PAY_KEY'));
    }

    /**
     * @return XftPayConfig
     */
    public function getConfig(): XftPayConfig
    {
        return $this->config;
    }

    /**
     * @param $outOrderId
     * @param integer $amount 单位: 分
     * @param string $subject
     * @param string $body
     * @param string $description
     * @return \by\infrastructure\base\CallResult
     */
    public function getPayUrl($outOrderId, $amount, $subject = 'VIP', $body = 'VIP', $description = 'VIP')
    {
        $body = [
            'app_id' => $this->config->getAppId(),
            'merchant_code' => $this->config->getMerchantCode(),
            'out_trade_no' => $outOrderId,
            'product' => 'ALIPAY_H5',
            'client_ip' => $this->config->getClientIp(),
            'amount' => $amount,
            'subject' => $subject,
            'body' => $body,
            'description' => $description,
            'sign_type' => 'MD5'
        ];
        $sign = SignTool::sign($body, $this->config);
        $body['sign'] = $sign;
        $http = (new HttpRequest())
            ->contentType('application/json')
            ->header('charset', 'UTF-8')
            ->post(self::Gateway1, json_encode($body));
        if ($http->success) {
            $content = $http->getBody()->getContents();
            $ret = json_decode($content, JSON_OBJECT_AS_ARRAY);
            if (array_key_exists('state', $ret)) {
                $state = $ret['state'];
                $state_msg = '';
                if (array_key_exists($state, $this->orderState)) {
                    $state_msg = $this->orderState[$state];
                }
                $sign_type = $ret['sign_type'];
                $sign = $ret['sign'];
                var_dump($sign);
//                $localSign = SignTool::sign($ret, $this->config);
//                if ($sign != $localSign) {
//                    return CallResultHelper::fail('验证签名失败' . $content);
//                }
                $formatRet = [
                    'state' => $state,
                    'state_msg' => $state_msg,
                    'out_trade_no' => $ret['out_trade_no'],
                    'credential' => $ret['credential']
                ];

                return CallResultHelper::success($formatRet);
            } else {
                return CallResultHelper::fail('返回参数错误' . $content);
            }
        } else {
            return CallResultHelper::fail('请求失败' . json_encode($body));
        }
    }

    protected $orderState = [
        '00' => '支付成功',
        '01' => '支付失败',
        '03' => '部分退款', '04' => '全部退款',
        '05' => '退款中', '06' => '已撤销',
        '09' => '待支付', '98' => '已关闭',
        '99' => '支付超时'
    ];


}
