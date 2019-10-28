<?php


namespace by\component\xft_pay;


class XftPayConfig
{
    protected $merchant_code;
    protected $key;
    protected $app_id;
    protected $clientIp;
    protected $notifyUrl;

    /**
     * XftPayConfig constructor.
     * @param $merchant_code
     * @param $key
     * @param $app_id
     */
    public function __construct($merchant_code = '1010174934854402049', $key = '6C7C97D68C7DB148DE678B4F5827D2F0', $app_id = '969037206616276993')
    {
        $this->merchant_code = $merchant_code;
        $this->key = $key;
        $this->app_id = $app_id;
        $this->setClientIp('127.0.0.1');
        $this->setNotifyUrl('');
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * @param mixed $notifyUrl
     */
    public function setNotifyUrl($notifyUrl): void
    {
        $this->notifyUrl = $notifyUrl;
    }

    /**
     * @return mixed
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @param mixed $clientIp
     */
    public function setClientIp($clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * @return mixed
     */
    public function getMerchantCode()
    {
        return $this->merchant_code;
    }

    /**
     * @param mixed $merchant_code
     */
    public function setMerchantCode($merchant_code): void
    {
        $this->merchant_code = $merchant_code;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param mixed $app_id
     */
    public function setAppId($app_id): void
    {
        $this->app_id = $app_id;
    }
}
