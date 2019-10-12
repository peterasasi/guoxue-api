<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_picture")
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $relative_path;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $original_name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $save_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $ext;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $md5;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $sha1;

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
    private $w;

    /**
     * @ORM\Column(type="integer")
     */
    private $h;

    /**
     * @ORM\Column(type="string", length=256, options={"default"=""})
     */
    private $ossKey;

    /**
     * @ORM\Column(type="string", length=64, options={"default"=""})
     */
    private $ossType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelativePath(): ?string
    {
        return $this->relative_path;
    }

    public function setRelativePath(string $relative_path): self
    {
        $this->relative_path = $relative_path;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->original_name;
    }

    public function setOriginalName(string $original_name): self
    {
        $this->original_name = $original_name;

        return $this;
    }

    public function getSaveName(): ?string
    {
        return $this->save_name;
    }

    public function setSaveName(string $save_name): self
    {
        $this->save_name = $save_name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }

    public function getSha1(): ?string
    {
        return $this->sha1;
    }

    public function setSha1(string $sha1): self
    {
        $this->sha1 = $sha1;

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

    public function getOssKey(): ?string
    {
        return $this->ossKey;
    }

    public function setOssKey(string $ossKey): self
    {
        $this->ossKey = $ossKey;

        return $this;
    }

    public function getOssType(): ?string
    {
        return $this->ossType;
    }

    public function setOssType(string $ossType): self
    {
        $this->ossType = $ossType;

        return $this;
    }
}
