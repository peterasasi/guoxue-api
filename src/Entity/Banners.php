<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_banners")
 * @ORM\Entity(repositoryClass="App\Repository\BannersRepository")
 */
class Banners extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $position;

    /**
     * @ORM\Column(type="bigint")
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
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @ORM\Column(type="bigint")
     */
    private $startTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $jumpUrl;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $imgUrl;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $jumpType;

    /**
     * @ORM\Column(type="integer")
     */
    private $w;

    /**
     * @ORM\Column(type="integer")
     */
    private $h;

    /**
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $clickNums;

    public function __construct()
    {
        parent::__construct();
        $this->setClickNums(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

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

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    public function setStartTime(int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getJumpUrl(): ?string
    {
        return $this->jumpUrl;
    }

    public function setJumpUrl(string $jumpUrl): self
    {
        $this->jumpUrl = $jumpUrl;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getJumpType(): ?string
    {
        return $this->jumpType;
    }

    public function setJumpType(string $jumpType): self
    {
        $this->jumpType = $jumpType;

        return $this;
    }

    public function getW(): ?int
    {
        return $this->w;
    }

    public function setW(int $w): self
    {
        $this->w = $w;

        return $this;
    }

    public function getH(): ?int
    {
        return $this->h;
    }

    public function setH(int $h): self
    {
        $this->h = $h;

        return $this;
    }

    public function getClickNums(): ?int
    {
        return $this->clickNums;
    }

    public function setClickNums(?int $clickNums): self
    {
        $this->clickNums = $clickNums;

        return $this;
    }

}
