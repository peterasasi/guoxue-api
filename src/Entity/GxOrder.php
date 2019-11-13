<?php

namespace App\Entity;

use App\Common\PayWayConst;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GxOrderRepository")
 */
class GxOrder extends BaseEntity
{
    const PayInitial = 0;

    const Paid = 1;

    const PaidFail = -1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $orderNo;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=4)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=4)
     */
    private $arrivalAmount;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=4)
     */
    private $fee;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $remark;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="smallint")
     */
    private $processed;

    /**
     * @ORM\Column(type="smallint")
     */
    private $payStatus;

    /**
     * @ORM\Column(type="bigint")
     */
    private $paidTime;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $exceptionMsg;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $createMonth;

    /**
     * @ORM\Column(type="integer")
     */
    private $vipItemId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $projectId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $sign;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $payRetOrderId;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $showJumpUrl;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $pw;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $merchantCode;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=3)
     */
    private $extraAmount;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $payConfig;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $username;

    public function __construct()
    {
        parent::__construct();
        $this->setExceptionMsg('');
        $this->setPaidTime(0);
        $this->setPayStatus(self::PayInitial);
        $this->setProcessed(0);
        $this->setRemark('');
        $this->setFee(0);
        $this->setCreateMonth(date("Ym", time()));
        $this->setVipItemId(0);
        $this->setPayRetOrderId('');
        $this->setSign('');
        $this->setShowJumpUrl('');
        $this->setPw(PayWayConst::PW000);
        $this->setMerchantCode('');
        $this->setPayConfig('');
        $this->setUsername('');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNo(): ?string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): self
    {
        $this->orderNo = $orderNo;

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

    public function getArrivalAmount()
    {
        return $this->arrivalAmount;
    }

    public function setArrivalAmount($arrivalAmount): self
    {
        $this->arrivalAmount = $arrivalAmount;

        return $this;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setFee($fee): self
    {
        $this->fee = $fee;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getProcessed(): ?int
    {
        return $this->processed;
    }

    public function setProcessed(int $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    public function getPayStatus(): ?int
    {
        return $this->payStatus;
    }

    public function setPayStatus(int $payStatus): self
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    public function getPaidTime(): ?int
    {
        return $this->paidTime;
    }

    public function setPaidTime(int $paidTime): self
    {
        $this->paidTime = $paidTime;

        return $this;
    }

    public function getExceptionMsg(): ?string
    {
        return $this->exceptionMsg;
    }

    public function setExceptionMsg(string $exceptionMsg): self
    {
        $this->exceptionMsg = $exceptionMsg;

        return $this;
    }

    public function getCreateMonth(): ?string
    {
        return $this->createMonth;
    }

    public function setCreateMonth(string $createMonth): self
    {
        $this->createMonth = $createMonth;

        return $this;
    }

    public function getVipItemId(): ?int
    {
        return $this->vipItemId;
    }

    public function setVipItemId(int $vipItemId): self
    {
        $this->vipItemId = $vipItemId;

        return $this;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }

    public function getPayRetOrderId(): ?string
    {
        return $this->payRetOrderId;
    }

    public function setPayRetOrderId(string $payRetOrderId): self
    {
        $this->payRetOrderId = $payRetOrderId;

        return $this;
    }

    public function getShowJumpUrl(): ?string
    {
        return $this->showJumpUrl;
    }

    public function setShowJumpUrl(string $showJumpUrl): self
    {
        $this->showJumpUrl = $showJumpUrl;

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

    public function getPw(): ?string
    {
        return $this->pw;
    }

    public function setPw(string $pw): self
    {
        $this->pw = $pw;

        return $this;
    }

    public function getMerchantCode(): ?string
    {
        return $this->merchantCode;
    }

    public function setMerchantCode(string $merchantCode): self
    {
        $this->merchantCode = $merchantCode;

        return $this;
    }

    public function getExtraAmount()
    {
        return $this->extraAmount;
    }

    public function setExtraAmount($extraAmount): self
    {
        $this->extraAmount = $extraAmount;

        return $this;
    }

    public function getPayConfig(): ?string
    {
        return $this->payConfig;
    }

    public function setPayConfig(string $payConfig): self
    {
        $this->payConfig = $payConfig;

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
