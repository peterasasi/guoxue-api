<?php


namespace by\component\xft_pay;


class NotifyParams
{
    protected $outTradeNo;
    protected $thirdTradeNo;
    protected $amount;
    protected $sign;
    protected $signType;
    protected $clientIp;
    protected $created;
    protected $state;
    protected $merchantCode;
    protected $updateTime;



    public function __construct($data = [])
    {
        $this->setOutTradeNo($data['out_trade_no']);
        $this->setThirdTradeNo($data['third_trade_no']);
        $this->setAmount($data['amount']);
        $this->setSign($data['sign']);
        $this->setSignType($data['sign_type']);
        $this->setClientIp($data['client_ip']);
        $this->setCreated($data['created']);
        $this->setState($data['state']);
        $this->setUpdateTime($data['update_time']);
        $this->setMerchantCode($data['merchant_code']);
    }

    /**
     * @return mixed
     */
    public function getMerchantCode()
    {
        return $this->merchantCode;
    }

    /**
     * @param mixed $merchantCode
     */
    public function setMerchantCode($merchantCode): void
    {
        $this->merchantCode = $merchantCode;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    /**
     * @param mixed $outTradeNo
     */
    public function setOutTradeNo($outTradeNo): void
    {
        $this->outTradeNo = $outTradeNo;
    }

    /**
     * @return mixed
     */
    public function getThirdTradeNo()
    {
        return $this->thirdTradeNo;
    }

    /**
     * @param mixed $thirdTradeNo
     */
    public function setThirdTradeNo($thirdTradeNo): void
    {
        $this->thirdTradeNo = $thirdTradeNo;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->signType;
    }

    /**
     * @param mixed $signType
     */
    public function setSignType($signType): void
    {
        $this->signType = $signType;
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }
}
