<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserAddressRepository")
 */
class UserAddress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $cityAreaCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $cityAreaName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $townCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $townName;

    /**
     * @ORM\Column(type="smallint")
     */
    private $isDefault;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=18)
     */
    private $contactMobile;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $detail;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCityAreaCode(): ?string
    {
        return $this->cityAreaCode;
    }

    public function setCityAreaCode(string $cityAreaCode): self
    {
        $this->cityAreaCode = $cityAreaCode;

        return $this;
    }

    public function getCityAreaName(): ?string
    {
        return $this->cityAreaName;
    }

    public function setCityAreaName(string $cityAreaName): self
    {
        $this->cityAreaName = $cityAreaName;

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

    public function getIsDefault(): ?int
    {
        return $this->isDefault;
    }

    public function setIsDefault(int $isDefault): self
    {
        $this->isDefault = $isDefault;

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

    public function getContactMobile(): ?string
    {
        return $this->contactMobile;
    }

    public function setContactMobile(string $contactMobile): self
    {
        $this->contactMobile = $contactMobile;

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }
}
