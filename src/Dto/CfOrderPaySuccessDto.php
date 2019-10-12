<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Dto;


class CfOrderPaySuccessDto
{
    private $notifyUrl;
    private $payType;
    private $uniqueOrder;
    private $tradeNo;
    private $money;
    private $payTime;
    private $payload;

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
    public function getPayType()
    {
        return $this->payType;
    }

    /**
     * @param mixed $payType
     */
    public function setPayType($payType): void
    {
        $this->payType = $payType;
    }
    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getUniqueOrder()
    {
        return $this->uniqueOrder;
    }

    /**
     * @param mixed $uniqueOrder
     */
    public function setUniqueOrder($uniqueOrder): void
    {
        $this->uniqueOrder = $uniqueOrder;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    /**
     * @param mixed $tradeNo
     */
    public function setTradeNo($tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money): void
    {
        $this->money = $money;
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
}