<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * 商品主信息-不常更新、不常变动的信息，精简的信息
 * @ORM\Entity(repositoryClass="App\Repository\SpGoodsRepository")
 */
class SpGoods extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $subTitle;

    /**
     * 显示价格
     * @ORM\Column(type="integer", options={"comment"="显示价格,原价"})
     */
    private $showPrice;

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
    private $uid;


    /**
     * @ORM\Column(type="string", length=250, options={"comment"="电脑端使用主图"})
     */
    private $coverImg;
    /**
     * @ORM\Column(type="string", length=250, options={"comment"="手机端使用主图"})
     */
    private $smallCoverImg;

    /**
     * @ORM\Column(type="string", length=2000, options={"comment"="商品其它图片,电脑、手机端通用,不要超过10张"})
     */
    private $imgList;

    /**
     * @ORM\Column(type="integer")
     */
    private $sales;

    /**
     * @ORM\Column(type="integer")
     */
    private $monthlySales;

    /**
     * @ORM\Column(type="integer", options={"comment"="状态，1: 正常 0: 隐藏 -1: 已删除 "})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", options={"comment"="状态，1: 上架销售中 0: 已下架不能购买"})
     */
    private $shelfStatus;

    /**
     * @ORM\Column(type="bigint", options={"default"="0", "comment"="销售开始时间, 0是不约束"})
     */
    private $saleOpenTime;

    /**
     * @ORM\Column(type="bigint", options={"default"="0","comment"="销售结束时间, 0是不约束"})
     */
    private $saleEndTime;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SpPropertyValue")
     */
    private $propertyValues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpGoodsSku", mappedBy="goods", orphanRemoval=true)
     */
    private $spGoodsSkus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpGoodsPlace", mappedBy="goods", orphanRemoval=true)
     */
    private $goodsPlace;

    /**
     * 支持的商品服务标签
     * @ORM\ManyToMany(targetEntity="App\Entity\Datatree")
     */
    private $supportServices;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpCate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cate;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $weight;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $volume;

    public function __construct()
    {
        parent::__construct();
        $this->propertyValues = new ArrayCollection();
        $this->spGoodsSkus = new ArrayCollection();
        $this->goodsPlace = new ArrayCollection();
        $this->supportServices = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param mixed $sales
     */
    public function setSales($sales): void
    {
        $this->sales = $sales;
    }

    /**
     * @return mixed
     */
    public function getMonthlySales()
    {
        return $this->monthlySales;
    }

    /**
     * @param mixed $monthlySales
     */
    public function setMonthlySales($monthlySales): void
    {
        $this->monthlySales = $monthlySales;
    }


    /**
     * @return mixed
     */
    public function getShowPrice()
    {
        return $this->showPrice;
    }

    /**
     * @param mixed $showPrice
     */
    public function setShowPrice($showPrice): void
    {
        $this->showPrice = $showPrice;
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

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): self
    {
        $this->subTitle = $subTitle;

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
     * @return mixed
     */
    public function getCoverImg()
    {
        return $this->coverImg;
    }

    /**
     * @param mixed $coverImg
     */
    public function setCoverImg($coverImg): void
    {
        $this->coverImg = $coverImg;
    }

    /**
     * @return mixed
     */
    public function getSmallCoverImg()
    {
        return $this->smallCoverImg;
    }

    /**
     * @param mixed $smallCoverImg
     */
    public function setSmallCoverImg($smallCoverImg): void
    {
        $this->smallCoverImg = $smallCoverImg;
    }

    /**
     * @return mixed
     */
    public function getImgList()
    {
        return $this->imgList;
    }

    /**
     * @param mixed $imgList
     */
    public function setImgList($imgList): void
    {
        $this->imgList = $imgList;
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

    public function getShelfStatus(): ?int
    {
        return $this->shelfStatus;
    }

    public function setShelfStatus(int $shelfStatus): self
    {
        $this->shelfStatus = $shelfStatus;

        return $this;
    }

    public function getSaleOpenTime(): ?int
    {
        return $this->saleOpenTime;
    }

    public function setSaleOpenTime(int $saleOpenTime): self
    {
        $this->saleOpenTime = $saleOpenTime;

        return $this;
    }

    public function getSaleEndTime(): ?int
    {
        return $this->saleEndTime;
    }

    public function setSaleEndTime(int $saleEndTime): self
    {
        $this->saleEndTime = $saleEndTime;

        return $this;
    }

    /**
     * @return Collection|SpPropertyValue[]
     */
    public function getPropertyValues(): Collection
    {
        return $this->propertyValues;
    }

    public function addPropertyValue(SpPropertyValue $propertyValue): self
    {
        if (!$this->propertyValues->contains($propertyValue)) {
            $this->propertyValues[] = $propertyValue;
        }

        return $this;
    }

    public function removePropertyValue(SpPropertyValue $propertyValue): self
    {
        if ($this->propertyValues->contains($propertyValue)) {
            $this->propertyValues->removeElement($propertyValue);
        }

        return $this;
    }

    /**
     * @return Collection|SpGoodsSku[]
     */
    public function getSpGoodsSkus(): Collection
    {
        return $this->spGoodsSkus;
    }

    public function addSpGoodsSkus(SpGoodsSku $spGoodsSkus): self
    {
        if (!$this->spGoodsSkus->contains($spGoodsSkus)) {
            $this->spGoodsSkus[] = $spGoodsSkus;
            $spGoodsSkus->setGoods($this);
        }

        return $this;
    }

    public function removeSpGoodsSkus(SpGoodsSku $spGoodsSkus): self
    {
        if ($this->spGoodsSkus->contains($spGoodsSkus)) {
            $this->spGoodsSkus->removeElement($spGoodsSkus);
            // set the owning side to null (unless already changed)
            if ($spGoodsSkus->getGoods() === $this) {
                $spGoodsSkus->setGoods(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SpGoodsPlace[]
     */
    public function getGoodsPlace(): Collection
    {
        return $this->goodsPlace;
    }

    public function addGoodsPlace(SpGoodsPlace $goodsPlace): self
    {
        if (!$this->goodsPlace->contains($goodsPlace)) {
            $this->goodsPlace[] = $goodsPlace;
            $goodsPlace->setGoods($this);
        }

        return $this;
    }

    public function removeGoodsPlace(SpGoodsPlace $goodsPlace): self
    {
        if ($this->goodsPlace->contains($goodsPlace)) {
            $this->goodsPlace->removeElement($goodsPlace);
            // set the owning side to null (unless already changed)
            if ($goodsPlace->getGoods() === $this) {
                $goodsPlace->setGoods(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Datatree[]
     */
    public function getSupportServices(): Collection
    {
        return $this->supportServices;
    }

    public function addSupportService(Datatree $supportService): self
    {
        if (!$this->supportServices->contains($supportService)) {
            $this->supportServices[] = $supportService;
        }

        return $this;
    }

    public function removeSupportService(Datatree $supportService): self
    {
        if ($this->supportServices->contains($supportService)) {
            $this->supportServices->removeElement($supportService);
        }

        return $this;
    }

    public function getCate(): ?SpCate
    {
        return $this->cate;
    }

    public function setCate(?SpCate $cate): self
    {
        $this->cate = $cate;

        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function setVolume($volume): self
    {
        $this->volume = $volume;

        return $this;
    }

}
