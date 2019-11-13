<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WithdrawRepository")
 */
class Withdraw extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $auditStatus;

    /**
     * @ORM\Column(type="text")
     */
    private $toWalletInfo;

    /**
     * @ORM\Column(type="bigint")
     */
    private $auditUid;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $auditNick;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $username;

    public function __construct()
    {
        parent::__construct();
        $this->setMobile('');
        $this->setUsername('');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

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

    public function getUpdateTime(): ?int
    {
        return $this->updateTime;
    }

    public function setUpdateTime(int $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getAuditStatus(): ?int
    {
        return $this->auditStatus;
    }

    public function setAuditStatus(int $auditStatus): self
    {
        $this->auditStatus = $auditStatus;

        return $this;
    }

    public function getToWalletInfo(): ?string
    {
        return $this->toWalletInfo;
    }

    public function setToWalletInfo(string $toWalletInfo): self
    {
        $this->toWalletInfo = $toWalletInfo;

        return $this;
    }

    public function getAuditUid(): ?int
    {
        return $this->auditUid;
    }

    public function setAuditUid(int $auditUid): self
    {
        $this->auditUid = $auditUid;

        return $this;
    }

    public function getAuditNick(): ?string
    {
        return $this->auditNick;
    }

    public function setAuditNick(string $auditNick): self
    {
        $this->auditNick = $auditNick;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
