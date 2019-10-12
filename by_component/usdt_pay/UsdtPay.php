<?php


namespace by\component\usdt_pay;


use Dbh\SfCoreBundle\Common\ByEnv;

class UsdtPay
{
    const Gateway1 = 'https://s.currpay.com/payLink/mobile.html';

    const Gateway2 = 'https://s.starfireotc.com/payLink/mobile.html';

    protected $pickupUrl;
    protected $receiveUrl;
    protected $md5Key;
    protected $appkey;

    public function __construct()
    {
        $this->pickupUrl = ByEnv::get('USDT_PAY_PICKUP_URL');
        $this->receiveUrl = ByEnv::get('USDT_PAY_RECEIVE_URL');
        $this->md5Key = '372580edae4aec8116643cce6e5e44d0';
        $this->appkey = '15bf238cd94c40c2a3a9d6a5fc312c5a';
    }

    public function getPayUrl($outOrderId, $customerAmountCny, $gwType = 1) {
        $sign = $this->sign($outOrderId, $customerAmountCny);
        $url = self::Gateway1;
        if ($gwType === 2) {
            $url = self::Gateway2;
        }
        $params = [
            'outOrderId' => $outOrderId,
            'customerAmountCny' => $customerAmountCny,
            'APPKey' => $this->appkey,
            'pickupUrl' => $this->pickupUrl,
            'receiveUrl' => $this->receiveUrl,
            'signType' => 'MD5',
            'sign' => $sign
        ];

        return $url.'?'.http_build_query($params);
    }


    public function sign($outOrderId, $customerAmountCny, $pickupUrl = '', $receiveUrl = '', $signType = 'MD5') {
        if (empty($pickupUrl)) {
            $pickupUrl = $this->pickupUrl;
        }
        if (empty($receiveUrl)) {
            $receiveUrl = $this->receiveUrl;
        }
        return strtolower(md5($outOrderId.$pickupUrl.$receiveUrl.$customerAmountCny.$signType.$this->md5Key));
    }

    /**
     * 回调签名验证
     * @param $outOrderId
     * @param $orderId
     * @param $customerAmount
     * @param $customerAmountCny
     * @param $sign
     * @return bool
     */
    public function cbVerifySign($outOrderId, $orderId, $customerAmount, $customerAmountCny, $sign) {
        $md5Sign = md5($customerAmount.$customerAmountCny.$outOrderId.$orderId.'MD5success'.$this->md5Key);
        return strtolower($md5Sign) === strtolower($sign);
    }

    public function getSuccessStr() {
        return 'success';
    }
}
