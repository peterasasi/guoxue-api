<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatformWalletRepository")
 */
class PlatformWallet
{
    // 总余额
    const Balance = "balance";
    // 创始人
    const FundCreator = "fund_creator";
    // 手续费
    const Pay1Fee = "pay1_fee";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $typeNo;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=4)
     */
    private $balance;

    /**
     * @ORM\Column(type="integer")
     */
    private $profitRatio;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $remark;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeNo(): ?string
    {
        return $this->typeNo;
    }

    public function setTypeNo(string $typeNo): self
    {
        $this->typeNo = $typeNo;

        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getProfitRatio(): ?int
    {
        return $this->profitRatio;
    }

    public function setProfitRatio(int $profitRatio): self
    {
        $this->profitRatio = $profitRatio;

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
}
