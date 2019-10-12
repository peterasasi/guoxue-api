<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatformWalletLogRepository")
 */
class PlatformWalletLog extends BaseEntity
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
    private $walletId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $remark;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=4)
     */
    private $income;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWalletId(): ?int
    {
        return $this->walletId;
    }

    public function setWalletId(int $walletId): self
    {
        $this->walletId = $walletId;

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

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getIncome()
    {
        return $this->income;
    }

    public function setIncome($income): self
    {
        $this->income = $income;

        return $this;
    }
}
