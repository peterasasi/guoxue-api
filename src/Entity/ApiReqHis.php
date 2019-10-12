<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiReqHisRepository")
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="index_3", columns={"client_id", "service_type", "ymd"})})
 */
class ApiReqHis extends BaseEntity
{
    /**
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="service_type", type="string", length=64, nullable=false, options={"default"="","comment"="接口id"})
     */
    private $serviceType;

    /**
     * @ORM\Column(name="create_time", type="bigint", nullable=false, options={"default"="0","comment"="接口id"})
     */
    private $createTime;

    /**
     * @ORM\Column(name="ymd", type="integer", nullable=false, options={"default"="0","comment"="年月日"})
     */
    private $ymd;

    /**
     * @ORM\Column(name="cnt", type="integer", nullable=false,  options={"default"="0","comment"="次数"})
     */
    private $cnt;

    /**
     * @ORM\Column(name="client_id", type="string", length=32, nullable=false)
     */
    private $clientId;

    public function getId(): int
    {
        return $this->id;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    public function setServiceType(string $serviceType): self
    {
        $this->serviceType = $serviceType;

        return $this;
    }

    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getYmd(): int
    {
        return $this->ymd;
    }

    public function setYmd(int $ymd): self
    {
        $this->ymd = $ymd;

        return $this;
    }

    public function getCnt(): int
    {
        return $this->cnt;
    }

    public function setCnt(int $cnt): self
    {
        $this->cnt = $cnt;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
