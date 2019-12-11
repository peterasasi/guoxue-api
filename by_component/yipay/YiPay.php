<?php


namespace by\component\yipay;


use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;

class YiPay
{
    protected $url = "http://www.kanfnw.ltd:7095/api/getQrCode";
    protected $key = "ppak164549";
    protected $code = "64822jdj84jfikd";
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
    public function alipay($orderNo, $amount, $notifyUrl, $payType = '1')
    {
        $data = [
            'amount' => strval($amount),
            'code' => $this->code,
            'orderNumber' => $orderNo,
            'notifyUrl' => urlencode($notifyUrl),
            'type' => $payType,
            'key' => $this->key
        ];
//        $data['urlType'] = '2';

        $data['token'] = md5(json_encode($data));
        if ($this->isDebug) {
            var_dump($data);
        }
        $data['urlType'] = '2';

//        unset($data['code']);

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
            if (array_key_exists('state', $ret)) {
                $code = intval($ret['state']);
                if ($code === 1) {
                    if (array_key_exists('msg', $ret)) {
                        return CallResultHelper::success($ret['msg'], $ret);
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
