<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserWalletLogRepository")
 */
class UserWalletLog extends BaseEntity
{
    // 收入
    const LogTypeDeposit = 'deposit';
    // 支出
    const LogTypeWithdraw = 'withdraw';
    // 冻结资金
    const LogTypeFreeze = 'freeze';
    // 解冻资金
    const LogTypeUnfreeze = 'unfreeze';
    // 解冻资金回退到账户
    const LogTypeUnfreezeBack = 'unfreeze_back';

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
     * @ORM\Column(type="string", length=512)
     */
    private $content;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $changeMoney;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $logType;


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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getChangeMoney()
    {
        return $this->changeMoney;
    }

    public function setChangeMoney($changeMoney): self
    {
        $this->changeMoney = $changeMoney;

        return $this;
    }

    public function getLogType(): ?string
    {
        return $this->logType;
    }

    public function setLogType(string $logType): self
    {
        $this->logType = $logType;

        return $this;
    }

}
