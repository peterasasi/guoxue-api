<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class ZmfVCodeProductEvent extends Event
{
    protected $customerOutNo;
    protected $payload;
    protected $timestamp;

    public function __construct($customerOutNo, $payload = [])
    {
        $this->customerOutNo = $customerOutNo;
        $this->payload = $payload;
        $this->timestamp = time();
    }

    /**
     * @return mixed
     */
    public function getCustomerOutNo()
    {
        return $this->customerOutNo;
    }

    /**
     * @param mixed $customerOutNo
     */
    public function setCustomerOutNo($customerOutNo): void
    {
        $this->customerOutNo = $customerOutNo;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}
