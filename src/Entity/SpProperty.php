<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpPropertyRepository")
 */
class SpProperty
{
    /**
     * 单选属性值
     */
    const SingleProperty = 'single';
    /**
     * 多选属性值
     */
    const MultipleProperty = 'multiple';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpPropertyValue", mappedBy="prop")
     */
    private $spPropertyValues;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $propType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isColor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SpCate", inversedBy="spProperties")
     */
    private $cate;

    public function __construct()
    {
        $this->spPropertyValues = new ArrayCollection();
        $this->cate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|SpPropertyValue[]
     */
    public function getSpPropertyValues(): Collection
    {
        return $this->spPropertyValues;
    }

    public function addSpPropertyValue(SpPropertyValue $spPropertyValue): self
    {
        if (!$this->spPropertyValues->contains($spPropertyValue)) {
            $this->spPropertyValues[] = $spPropertyValue;
            $spPropertyValue->setPropId($this);
        }

        return $this;
    }

    public function removeSpPropertyValue(SpPropertyValue $spPropertyValue): self
    {
        if ($this->spPropertyValues->contains($spPropertyValue)) {
            $this->spPropertyValues->removeElement($spPropertyValue);
            // set the owning side to null (unless already changed)
            if ($spPropertyValue->getPropId() === $this) {
                $spPropertyValue->setPropId(null);
            }
        }

        return $this;
    }

    public function getPropType(): ?string
    {
        return $this->propType;
    }

    public function setPropType(string $propType): self
    {
        $this->propType = $propType;

        return $this;
    }

    public function getIsSale(): ?bool
    {
        return $this->isSale;
    }

    public function setIsSale(bool $isSale): self
    {
        $this->isSale = $isSale;

        return $this;
    }

    public function getIsColor(): ?bool
    {
        return $this->isColor;
    }

    public function setIsColor(bool $isColor): self
    {
        $this->isColor = $isColor;

        return $this;
    }

    /**
     * @return Collection|SpCate[]
     */
    public function getCate(): Collection
    {
        return $this->cate;
    }

    public function addCate(SpCate $cate): self
    {
        if (!$this->cate->contains($cate)) {
            $this->cate[] = $cate;
        }

        return $this;
    }

    public function removeCate(SpCate $cate): self
    {
        if ($this->cate->contains($cate)) {
            $this->cate->removeElement($cate);
        }

        return $this;
    }

    public function removeAllCate() {
        return $this->cate->clear();
    }
}
