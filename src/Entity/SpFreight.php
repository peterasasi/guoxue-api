<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpFreightRepository")
 */
class SpFreight extends BaseEntity
{

    // 免运费
    const FreightTypeFree = 1;

    // 运费到付
    const FreightTypeCollect = 2;

    // 运费预付 - 使用运费模板来计算运费价格
    const FreightTypePrepaid = 3;

    const MethodCount = "count"; // 件数

    const MethodWeight = "weight"; // 重量

    const MethodVolume = "volume"; // 体积

    const LogisticsEms = "ems"; // EMS
    const LogisticsExpress = "express"; // 快递
    const LogisticsSurfaceMail = "surface_mail";// 平邮


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $method;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * 物流类型 (快递: express,平邮: surface_mail,EMS: ems)
     * @ORM\Column(type="string", length=64, options={"comment"="物流方式,快递，平邮,EMS"})
     */
    private $logisticsType;

    /**
     * @ORM\Column(type="text", options={"comment"="价格定义JSON字符串,含送达地区、价格"})
     */
    private $priceDefine;

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
    private $freightType;

    /**
     * @ORM\Column(type="text", options={"comment"="条件包邮定义JSON字符串,含送达地区、包邮条件重量、件数、金额"})
     */
    private $freeCondition;

    /**
     * @ORM\Column(type="smallint")
     */
    private $enableFreeCond;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogisticsType(): ?string
    {
        return $this->logisticsType;
    }

    public function setLogisticsType(string $logisticsType): self
    {
        $this->logisticsType = $logisticsType;

        return $this;
    }

    public function getPriceDefine(): ?string
    {
        return $this->priceDefine;
    }

    public function setPriceDefine(string $priceDefine): self
    {
        $this->priceDefine = $priceDefine;

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

    public function getFreightType(): ?string
    {
        return $this->freightType;
    }

    public function setFreightType(string $freightType): self
    {
        $this->freightType = $freightType;

        return $this;
    }

    public function getFreeCondition(): ?string
    {
        return $this->freeCondition;
    }

    public function setFreeCondition(string $freeCondition): self
    {
        $this->freeCondition = $freeCondition;

        return $this;
    }

    public function getEnableFreeCond(): ?int
    {
        return $this->enableFreeCond;
    }

    public function setEnableFreeCond(int $enableFreeCond): self
    {
        $this->enableFreeCond = $enableFreeCond;

        return $this;
    }
}
