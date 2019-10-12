<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpForeCateRepository")
 */
class SpForeCate extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $parentId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $level;

    /**
     * @ORM\Column(type="integer",  options={"default"="0","comment"="排序"})
     */
    private $sort;

    /**
     * @ORM\Column(type="smallint", options={"default"="0","comment"="是否为叶子节点"})
     */
    private $leaf;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SpGoods")
     */
    private $goods;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    public function __construct()
    {
        parent::__construct();
        $this->goods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

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

    public function getLeaf(): ?int
    {
        return $this->leaf;
    }

    public function setLeaf(int $leaf): self
    {
        $this->leaf = $leaf;

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

    /**
     * @return ArrayCollection|SpGoods[]
     */
    public function getGoods(): Collection
    {
        return $this->goods;
    }

    public function addGood(SpGoods $good): self
    {
        if (!$this->goods->contains($good)) {
            $this->goods[] = $good;
        }

        return $this;
    }

    public function removeGood(SpGoods $good): self
    {
        if ($this->goods->contains($good)) {
            $this->goods->removeElement($good);
        }

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
}
