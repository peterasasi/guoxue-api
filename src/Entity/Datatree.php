<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_datatree", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_code", columns={"code"})})
 * @ORM\Entity(repositoryClass="App\Repository\DatatreeRepository")
 */
class Datatree extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $alias;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $sort;

    /**
     * @ORM\Column(type="bigint",nullable=false)
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint",nullable=false)
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=255,nullable=false)
     */
    private $parents;

    /**
     * @ORM\Column(type="bigint")
     */
    private $parentId;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $notes;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $icon;

    /**
     * @ORM\Column(type="smallint")
     */
    private $dataLevel;

    public function __construct()
    {
        parent::__construct();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

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

    public function getParents(): ?string
    {
        return $this->parents;
    }

    public function setParents(string $parents): self
    {
        $this->parents = $parents;

        return $this;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getDataLevel(): ?int
    {
        return $this->dataLevel;
    }

    public function setDataLevel(int $dataLevel): self
    {
        $this->dataLevel = $dataLevel;

        return $this;
    }

}
