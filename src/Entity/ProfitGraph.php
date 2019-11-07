<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="profit_graph", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_uid_333", columns={"uid"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProfitGraphRepository")
 */
class ProfitGraph extends BaseEntity
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
     * @ORM\Column(type="string", length=12)
     */
    private $vipLevel;

    /**
     * @ORM\Column(type="smallint")
     */
    private $active;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $inviteCount;

    /**
     * @ORM\Column(type="bigint")
     */
    private $parentUid;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=4)
     */
    private $totalIncome;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mobile;

    /**
     * @ORM\Column(type="text")
     */
    private $family;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $username;

    public function __construct()
    {
        parent::__construct();
        $this->setUsername('');
        $this->setActive(1);
        $this->setTotalIncome(0);
        $this->setParentUid(0);
        $this->setVipLevel(0);
        $this->setInviteCount(0);
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

    public function getVipLevel(): ?string
    {
        return $this->vipLevel;
    }

    public function setVipLevel(string $vipLevel): self
    {
        $this->vipLevel = $vipLevel;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

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

    public function getInviteCount(): ?int
    {
        return $this->inviteCount;
    }

    public function setInviteCount(int $inviteCount): self
    {
        $this->inviteCount = $inviteCount;

        return $this;
    }

    public function getParentUid(): ?int
    {
        return $this->parentUid;
    }

    public function setParentUid(int $parentUid): self
    {
        $this->parentUid = $parentUid;

        return $this;
    }


    public function getTotalIncome()
    {
        return $this->totalIncome;
    }

    public function setTotalIncome($totalIncome): self
    {
        $this->totalIncome = $totalIncome;

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

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

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
