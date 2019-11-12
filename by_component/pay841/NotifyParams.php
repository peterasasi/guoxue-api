<?php


namespace by\component\pay841;


class NotifyParams
{
    protected $merchantNum;
    protected $orderNo;
    protected $platformOrderNo;
    protected $amount;//支付的价格(单位：元)
    protected $attch;//
    protected $state;//订单状态【1代表支付成功】
    protected $payTime;//支付成功时的时间,yyyy-MM-dd HH:mm:ss
    protected $sign;//签名【md5(订单状态+商户号+商户订单号+支付金额+商户秘钥)】
    protected $actualPayAmount;//实际支付金额

    public function __construct($data)
    {
        if (is_array($data)) {
            array_key_exists('state', $data) && $this->setState($data['state']);
            array_key_exists('sign', $data) && $this->setSign($data['sign']);
            array_key_exists('payTime', $data) && $this->setPayTime($data['payTime']);
            array_key_exists('actualPayAmount', $data) && $this->setActualPayAmount($data['actualPayAmount']);
            array_key_exists('platformOrderNo', $data) && $this->setPlatformOrderNo($data['platformOrderNo']);
            array_key_exists('amount', $data) && $this->setAmount($data['amount']);
            array_key_exists('attch', $data) && $this->setAttch($data['attch']);
            array_key_exists('merchantNum', $data) && $this->setMerchantNum($data['merchantNum']);
            array_key_exists('orderNo', $data) && $this->setOrderNo($data['orderNo']);
        }
    }

    /**
     * @return mixed
     */
    public function getMerchantNum()
    {
        return $this->merchantNum;
    }

    /**
     * @param mixed $merchantNum
     */
    public function setMerchantNum($merchantNum): void
    {
        $this->merchantNum = $merchantNum;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getPlatformOrderNo()
    {
        return $this->platformOrderNo;
    }

    /**
     * @param mixed $platformOrderNo
     */
    public function setPlatformOrderNo($platformOrderNo): void
    {
        $this->platformOrderNo = $platformOrderNo;
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
    public function getAttch()
    {
        return $this->attch;
    }

    /**
     * @param mixed $attch
     */
    public function setAttch($attch): void
    {
        $this->attch = $attch;
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

    /**
     * @return mixed
     */
    public function getPayTime()
    {
        return $this->payTime;
    }

    /**
     * @param mixed $payTime
     */
    public function setPayTime($payTime): void
    {
        $this->payTime = $payTime;
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
    public function getActualPayAmount()
    {
        return $this->actualPayAmount;
    }

    /**
     * @param mixed $actualPayAmount
     */
    public function setActualPayAmount($actualPayAmount): void
    {
        $this->actualPayAmount = $actualPayAmount;
    }
}
