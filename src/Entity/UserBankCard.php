<?php

namespace App\Entity;

use by\component\encrypt\des\Des;
use by\infrastructure\constants\StatusEnum;
use Dbh\SfCoreBundle\Common\ByEnv;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserBankCardRepository")
 * @ORM\Table(name="user_bank_card")
 *
 */
class UserBankCard extends BaseEntity
{
    /**
     * 信用卡
     */
    const TypeCredit = 1;

    /**
     * 借记卡、储蓄卡
     */
    const TypeDebit = 2;

    // 支付卡
    const UsagePaymentCard =  1;

    // 结算卡
    const UsageBalanceCard = 2;

    // 认证卡
    const UsageVerifyCard = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * 卡类型
     * 1 = 信用卡 2 = 借记卡、储蓄卡
     * @ORM\Column(type="integer")
     */
    private $cardType;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $cardNo;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $openingBank;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $branchBank;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $idNo;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $mobile;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * 用途
     * @ORM\Column(type="integer")
     */
    private $cardUsage;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $cardCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $verify;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $frontImg;

    /**
     * @ORM\Column(type="integer")
     */
    private $master;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $frontImgId;

    /**
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $billDate;

    /**
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $repaymentDate;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $cvn2;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $expireDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $branchNo;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $payAgreeId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $withdrawAgreeId;

    public function __construct()
    {
        parent::__construct();
        $this->master = 0;
        $this->setStatus(StatusEnum::ENABLE);
        $this->setBranchNo('');
        $this->setPayAgreeId('');
        $this->setFrontImgId('');
        $this->setPayAgreeId('');
        $this->setExpireDate('');
        $this->setRepaymentDate(0);
        $this->setWithdrawAgreeId('');
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

    public function getCardType(): ?int
    {
        return $this->cardType;
    }

    public function setCardType(int $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCardNo(): ?string
    {
        return Des::decode($this->cardNo, ByEnv::get('APP_SECRET'));
    }

    public function setCardNo(string $cardNo): self
    {
        $this->cardNo = Des::encode($cardNo, ByEnv::get('APP_SECRET'));
        return $this;
    }

    public function getOpeningBank(): ?string
    {
        return $this->openingBank;
    }

    public function setOpeningBank(string $openingBank): self
    {
        $this->openingBank = $openingBank;

        return $this;
    }

    public function getBranchBank(): ?string
    {
        return $this->branchBank;
    }

    public function setBranchBank(string $branchBank): self
    {
        $this->branchBank = $branchBank;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdNo(): ?string
    {
        return Des::decode($this->idNo, ByEnv::get('APP_SECRET'));
    }

    public function setIdNo(string $idNo): self
    {
        $this->idNo = Des::encode($idNo, ByEnv::get('APP_SECRET'));
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

    public function getCardUsage(): ?int
    {
        return $this->cardUsage;
    }

    public function setCardUsage(int $cardUsage): self
    {
        $this->cardUsage = $cardUsage;

        return $this;
    }

    public function getCardCode(): ?string
    {
        return $this->cardCode;
    }

    public function setCardCode(string $cardCode): self
    {
        $this->cardCode = $cardCode;

        return $this;
    }

    public function getVerify(): ?int
    {
        return $this->verify;
    }

    public function setVerify(int $verify): self
    {
        $this->verify = $verify;

        return $this;
    }

    public function getFrontImg(): ?string
    {
        return $this->frontImg;
    }

    public function setFrontImg(string $frontImg): self
    {
        $this->frontImg = $frontImg;

        return $this;
    }

    public function getMaster(): ?int
    {
        return $this->master;
    }

    public function setMaster(int $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getFrontImgId(): ?string
    {
        return $this->frontImgId;
    }

    public function setFrontImgId(string $frontImgId): self
    {
        $this->frontImgId = $frontImgId;

        return $this;
    }

    public function getBillDate(): ?int
    {
        return $this->billDate;
    }

    public function setBillDate(int $billDate): self
    {
        $this->billDate = $billDate;

        return $this;
    }

    public function getRepaymentDate(): ?int
    {
        return $this->repaymentDate;
    }

    public function setRepaymentDate(int $repaymentDate): self
    {
        $this->repaymentDate = $repaymentDate;

        return $this;
    }

    public function getCvn2(): ?string
    {
        return Des::decode($this->cvn2, ByEnv::get('APP_SECRET'));
    }

    public function setCvn2(string $cvn2): self
    {
        $this->cvn2 = Des::encode($cvn2, ByEnv::get('APP_SECRET'));

        return $this;
    }

    public function getExpireDate(): ?string
    {
        return $this->expireDate;
    }

    public function setExpireDate(string $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBranchNo(): ?string
    {
        return $this->branchNo;
    }

    public function setBranchNo(string $branchNo): self
    {
        $this->branchNo = $branchNo;

        return $this;
    }

    public function getPayAgreeId(): ?string
    {
        return $this->payAgreeId;
    }

    public function setPayAgreeId(string $payAgreeId): self
    {
        $this->payAgreeId = $payAgreeId;

        return $this;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'idNo' => $this->idNo,
            'uid' => $this->uid,
            'card_type' => $this->cardType,
            'card_no' => $this->cardNo,
            'opening_bank' => $this->openingBank,
            'branch_bank' => $this->branchBank,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'create_time' => $this->createTime,
            'update_time' => $this->updateTime,
            'card_usage' => $this->cardUsage,
            'card_code' => $this->cardCode,
            'verify' => $this->verify,
            'front_img' => $this->frontImg,
            'master' => $this->master,
            'front_img_id' => $this->frontImgId,
            'bill_date' => $this->billDate,
            'repayment_date' => $this->repaymentDate,
            'cvn2' => $this->cvn2,
            'expire_date' => $this->expireDate,
            'status' => $this->status,
            'branch_no' => $this->branchNo,
            'pay_agree_id' => $this->payAgreeId,
            'withdraw_agree_id' => $this->withdrawAgreeId
        ];
    }

    public function getWithdrawAgreeId(): ?string
    {
        return $this->withdrawAgreeId;
    }

    public function setWithdrawAgreeId(string $withdrawAgreeId): self
    {
        $this->withdrawAgreeId = $withdrawAgreeId;

        return $this;
    }
}
