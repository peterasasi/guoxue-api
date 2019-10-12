<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpGoodsPlaceRepository")
 */
class SpGoodsPlace
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
    private $freightTplId;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $countryName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $provinceCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $provinceName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $cityCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $cityName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $areaCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $areaName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $townCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $townName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpGoods", inversedBy="goodsPlace")
     * @ORM\JoinColumn(nullable=false)
     */
    private $goods;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFreightTplId(): ?int
    {
        return $this->freightTplId;
    }

    public function setFreightTplId(int $freightTplId): self
    {
        $this->freightTplId = $freightTplId;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(string $provinceCode): self
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    public function getProvinceName(): ?string
    {
        return $this->provinceName;
    }

    public function setProvinceName(string $provinceName): self
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    public function getCityCode(): ?string
    {
        return $this->cityCode;
    }

    public function setCityCode(string $cityCode): self
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getAreaCode(): ?string
    {
        return $this->areaCode;
    }

    public function setAreaCode(string $areaCode): self
    {
        $this->areaCode = $areaCode;

        return $this;
    }

    public function getAreaName(): ?string
    {
        return $this->areaName;
    }

    public function setAreaName(string $areaName): self
    {
        $this->areaName = $areaName;

        return $this;
    }

    public function getTownCode(): ?string
    {
        return $this->townCode;
    }

    public function setTownCode(string $townCode): self
    {
        $this->townCode = $townCode;

        return $this;
    }

    public function getTownName(): ?string
    {
        return $this->townName;
    }

    public function setTownName(string $townName): self
    {
        $this->townName = $townName;

        return $this;
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
}
