<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpCateRepository")
 */
class SpCate
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
    private $parentId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", options={"default"="0","comment"="排序"})
     */
    private $sort;

    /**
     * @ORM\Column(type="smallint", options={"default"="0","comment"="是否为叶子节点"})
     */
    private $leaf;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SpProperty", mappedBy="cate")
     */
    private $spProperties;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SpBrand", mappedBy="cate")
     */
    private $spBrands;

    public function __construct()
    {
        $this->spProperties = new ArrayCollection();
        $this->spBrands = new ArrayCollection();
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection|SpProperty[]
     */
    public function getSpProperties(): Collection
    {
        return $this->spProperties;
    }

    public function addSpProperty(SpProperty $spProperty): self
    {
        if (!$this->spProperties->contains($spProperty)) {
            $this->spProperties[] = $spProperty;
            $spProperty->addCate($this);
        }

        return $this;
    }

    public function removeSpProperty(SpProperty $spProperty): self
    {
        if ($this->spProperties->contains($spProperty)) {
            $this->spProperties->removeElement($spProperty);
            $spProperty->removeCate($this);
        }

        return $this;
    }

    /**
     * @return Collection|SpBrand[]
     */
    public function getSpBrands(): Collection
    {
        return $this->spBrands;
    }

    public function addSpBrand(SpBrand $spBrand): self
    {
        if (!$this->spBrands->contains($spBrand)) {
            $this->spBrands[] = $spBrand;
            $spBrand->addCate($this);
        }

        return $this;
    }

    public function removeSpBrand(SpBrand $spBrand): self
    {
        if ($this->spBrands->contains($spBrand)) {
            $this->spBrands->removeElement($spBrand);
            $spBrand->removeCate($this);
        }

        return $this;
    }
}
