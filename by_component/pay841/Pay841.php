<?php


namespace by\component\pay841;


use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;

class Pay841
{
    protected $url = "http://api.841pay.com/api/startOrder";
    protected $key = "d6e8083503222e1361f0ebff599ad086";
    protected $merchantNum = '999';
    protected $isDebug = false;

    public function openDebug(): self {
        $this->isDebug = true;
        return $this;
    }

    /**
     * @param string $orderNo 长度32位以下
     * @param double $amount 元
     * @param $notifyUrl
     * @param $returnUrl
     * @param string $payType
     * @return \by\infrastructure\base\CallResult
     */
    public function alipay($orderNo, $amount, $notifyUrl, $returnUrl, $payType = 'alipay')
    {
        $data = [
            'merchantNum' => $this->merchantNum,
            'orderNo' => $orderNo,
            'amount' => strval($amount),
            'notifyUrl' => $notifyUrl,
            'returnUrl' => $returnUrl,
            'payType' => $payType,
            'attch' => '9864301',
//sign
// 签名【md5 32位小写(商户号+商户订单号+支付金额+异步通知地址+商户秘钥)】
// 签名参数拼接实例：sign = merchantNum + orderNo + amount + notifyUrl + Key;
        ];
        $data['sign'] = strtolower(md5($data['merchantNum'].$orderNo.$amount.$notifyUrl.$this->key));
        if ($this->isDebug) {
            var_dump($data);
        }
        $http = (new HttpRequest())
            ->contentType('application/x-www-form-urlencoded')
            ->header('charset', 'UTF-8')
            ->post($this->url, $data);
        if ($http->success) {
            $content = $http->getBody()->getContents();
            $ret = json_decode($content, JSON_OBJECT_AS_ARRAY);
            if ($this->isDebug) {
                var_dump($ret);
            }
            if (array_key_exists('code', $ret)) {
                $code = intval($ret['code']);
                if ($code === 200) {
                    if (array_key_exists('data', $ret) && array_key_exists('payUrl', $ret['data'])) {
                        return CallResultHelper::success($ret['data']['payUrl'], $ret['msg']);
                    }
                    return CallResultHelper::fail($ret, $ret['msg']);
                } else {
                    return CallResultHelper::fail($ret['msg']);
                }
            } else {
                return CallResultHelper::fail('Pay841返回数据缺少code'.$content);
            }
        } else {
            return CallResultHelper::fail('请求错误'.$http->error());
        }
    }



}
