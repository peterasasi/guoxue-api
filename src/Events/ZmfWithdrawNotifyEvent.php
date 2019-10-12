<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class ZmfWithdrawNotifyEvent extends Event
{
    protected $withdrawStatus;
    protected $orderNo;
    protected $payload;
    protected $timestamp;

    public function __construct($withdrawStatus, $orderNo, $payload = [])
    {
        $this->orderNo = $orderNo;
        $this->withdrawStatus = $withdrawStatus;
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
    public function getWithdrawStatus()
    {
        return $this->withdrawStatus;
    }

    /**
     * @param mixed $withdrawStatus
     */
    public function setWithdrawStatus($withdrawStatus): void
    {
        $this->withdrawStatus = $withdrawStatus;
    }

}
