<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoSourceRepository")
 */
class VideoSource
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
    private $vid;

    /**
     * @ORM\Column(type="string", length=64, options={"comment"="视频文件类型"})
     */
    private $vType;

    /**
     *
     * @ORM\Column(type="string", length=256, options={"default"="", "comment"="视频播放地址url"})
     */
    private $vUri;

    /**
     * @ORM\Column(type="string", length=64, options={"default"="互联网", "comment"="视频来源"})
     */
    private $comeFrom;

    /**
     * @ORM\Column(type="bigint", options={"default"="0", "comment"="排序"})
     */
    private $sort;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * 视频来源唯一标识,防止重复插入数据
     * @ORM\Column(type="string", length=64)
     */
    private $srcKey;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $comeFromAlias;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVid(): ?int
    {
        return $this->vid;
    }

    public function setVid(int $vid): self
    {
        $this->vid = $vid;

        return $this;
    }

    public function getVType(): ?string
    {
        return $this->vType;
    }

    public function setVType(string $vType): self
    {
        $this->vType = $vType;

        return $this;
    }

    public function getVUri(): ?string
    {
        return $this->vUri;
    }

    public function setVUri(string $vUri): self
    {
        $this->vUri = $vUri;

        return $this;
    }

    public function getComeFrom(): ?string
    {
        return $this->comeFrom;
    }

    public function setComeFrom(string $comeFrom): self
    {
        $this->comeFrom = $comeFrom;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSrcKey(): ?string
    {
        return $this->srcKey;
    }

    public function setSrcKey(string $srcKey): self
    {
        $this->srcKey = $srcKey;

        return $this;
    }

    public function getComeFromAlias(): ?string
    {
        return $this->comeFromAlias;
    }

    public function setComeFromAlias(string $comeFromAlias): self
    {
        $this->comeFromAlias = $comeFromAlias;

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
}
