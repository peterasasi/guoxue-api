<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuggestRepository")
 */
class Suggest extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $content;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $uid;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $userInfo;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $qq;

    /**
     * @ORM\Column(type="integer")
     */
    private $procStatus;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

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

    public function getUserInfo(): ?string
    {
        return $this->userInfo;
    }

    public function setUserInfo(string $userInfo): self
    {
        $this->userInfo = $userInfo;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getQq(): ?string
    {
        return $this->qq;
    }

    public function setQq(string $qq): self
    {
        $this->qq = $qq;

        return $this;
    }

    public function getProcStatus(): ?int
    {
        return $this->procStatus;
    }

    public function setProcStatus(int $procStatus): self
    {
        $this->procStatus = $procStatus;

        return $this;
    }
}
