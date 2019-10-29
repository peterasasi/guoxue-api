<?php


namespace by\component\xft_pay;


use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\ByEnv;

class XftPay
{
    const Gateway1 = 'https://xlink.91xft.cn/transaction/unify/charge/pay';

    protected $config;

    public function getSuccessStr() {
        return 'SUCCESS';
    }

    public function __construct()
    {
        $this->config = new XftPayConfig();
        $this->config->setClientIp(ByEnv::get('XFT_PAY_CLIENT_IP'));
        $this->config->setAppId(ByEnv::get('XFT_PAY_APP_ID'));
        $this->config->setMerchantCode(ByEnv::get('XFT_PAY_M_CODE'));
        $this->config->setKey(ByEnv::get('XFT_PAY_KEY'));
        $this->config->setNotifyUrl(ByEnv::get('XFT_PAY_NOTIFY_URL'));
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
            'notify_url' => $this->config->getNotifyUrl(),
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
            if (array_key_exists('result', $ret) && $ret['result']) {
                $data = $ret['data'];
                $message = $ret['message'];
//                var_dump($data);
                if (array_key_exists('state', $data)) {
                    $state = $data['state'];
                    $state_msg = '';
                    if (array_key_exists($state, $this->orderState)) {
                        $state_msg = $this->orderState[$state];
                    }
                    $sign_type = $data['sign_type'];
                    $sign = $data['sign'];
                    unset($data['sign']);
//                var_dump($sign);
                    $localSign = SignTool::sign($data, $this->config);
//                    var_dump($localSign);
                    if ($sign != $localSign) {
                        return CallResultHelper::fail('验证签名失败' . $content);
                    }
                    $failure_msg = $data['failure_msg'];
                    $formatRet = [
                        'state' => $state,
                        'state_msg' => $state_msg,
                        'out_trade_no' => $data['out_trade_no'],
                        'credential' => $data['credential']
                    ];
                    if ($state == '09') {
                        $credential = $data['credential'];
                        if (is_string($credential)) $credential = json_decode($credential, JSON_OBJECT_AS_ARRAY);
                        if (array_key_exists('h5_url', $credential)) {
                            return CallResultHelper::success($credential['h5_url']);
                        }
                    }
                    return CallResultHelper::fail($message.'-'.$failure_msg, $ret);
                } else {
                    return CallResultHelper::fail('返回参数错误' . $content);
                }
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
