<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class ZmfPayNotifyEvent extends Event
{
    protected $orderNo;
    protected $payStatus;
    protected $payload;
    protected $timestamp;

    public function __construct($payStatus, $orderNo, $payload = [])
    {
        $this->orderNo = $orderNo;
        $this->payStatus = $payStatus;
        $this->payload = $payload;
        $this->timestamp = time();
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
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
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
    public function getPayStatus()
    {
        return $this->payStatus;
    }

    /**
     * @param mixed $payStatus
     */
    public function setPayStatus($payStatus): void
    {
        $this->payStatus = $payStatus;
    }

}
