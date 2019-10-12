<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayOrderNotifyLogRepository")
 */
class PayOrderNotifyLog extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $payCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $notifyCount;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $notifyMsg;

    /**
     * @ORM\Column(type="smallint")
     */
    private $success;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayCode(): ?string
    {
        return $this->payCode;
    }

    public function setPayCode(string $payCode): self
    {
        $this->payCode = $payCode;

        return $this;
    }

    public function getNotifyCount(): ?int
    {
        return $this->notifyCount;
    }

    public function setNotifyCount(int $notifyCount): self
    {
        $this->notifyCount = $notifyCount;

        return $this;
    }

    public function getNotifyMsg(): ?string
    {
        return $this->notifyMsg;
    }

    public function setNotifyMsg(string $notifyMsg): self
    {
        $this->notifyMsg = $notifyMsg;

        return $this;
    }

    public function getSuccess(): ?int
    {
        return $this->success;
    }

    public function setSuccess(int $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }
}
