<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="sp_goods_sku", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_goods_no", columns={"uniq_goods_no"})})
 * @ORM\Entity(repositoryClass="App\Repository\SpGoodsSkuRepository")
 */
class SpGoodsSku extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpGoods", inversedBy="spGoodsSkus")
     * @ORM\JoinColumn(name="goods_id", referencedColumnName="id")
     */
    private $goods;

    /**
     * @ORM\Column(type="string", length=2000, options={"default"="","comment"="商品规格标记"})
     */
    private $specs;

    /**
     * @ORM\Column(type="bigint", options={"default"="0","comment"="该规格商品的初始库存"})
     */
    private $stock;

    /**
     * @ORM\Column(type="bigint", options={"default"="0","comment"="该规格商品的入库价"})
     */
    private $stockPrice;

    /**
     * @ORM\Column(type="bigint", options={"default"="0","comment"="该规格商品的销售价格"})
     */
    private $price;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=64, options={"default"="","comment"="该规格商品的外部货号,不能保证唯一"})
     */
    private $goodsNo;

    /**
     * @ORM\Column(type="string", length=64, options={"default"="","comment"="唯一性,该规格商品的系统自动生成的货号"})
     */
    private $uniqGoodsNo;

    /**
     * @ORM\Column(type="integer")
     */
    private $singleSku;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $skuIndex;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $pic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoods(): ?SpGoods
    {
        return $this->goods;
    }

    public function setGoods(?SpGoods $goods): self
    {
        $this->goods = $goods;

        return $this;
    }

    public function getSpecs(): ?string
    {
        return $this->specs;
    }

    public function setSpecs(string $specs): self
    {
        $this->specs = $specs;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getGoodsNo(): ?string
    {
        return $this->goodsNo;
    }

    public function setGoodsNo(string $goodsNo): self
    {
        $this->goodsNo = $goodsNo;

        return $this;
    }

    public function getStockPrice(): ?int
    {
        return $this->stockPrice;
    }

    public function setStockPrice(int $stockPrice): self
    {
        $this->stockPrice = $stockPrice;

        return $this;
    }

    public function getUniqGoodsNo(): ?string
    {
        return $this->uniqGoodsNo;
    }

    public function setUniqGoodsNo(string $uniqGoodsNo): self
    {
        $this->uniqGoodsNo = $uniqGoodsNo;

        return $this;
    }

    public function getSingleSku(): ?int
    {
        return $this->singleSku;
    }

    public function setSingleSku(int $singleSku): self
    {
        $this->singleSku = $singleSku;

        return $this;
    }

    public function getSkuIndex(): ?string
    {
        return $this->skuIndex;
    }

    public function setSkuIndex(string $skuIndex): self
    {
        $this->skuIndex = $skuIndex;

        return $this;
    }

    public function getPic(): ?string
    {
        return $this->pic;
    }

    public function setPic(string $pic): self
    {
        $this->pic = $pic;

        return $this;
    }
}
