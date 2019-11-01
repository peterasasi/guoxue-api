<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="xft_merchant", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_app_id", columns={"app_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\XftMerchantRepository")
 */
class XftMerchant
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
    private $appId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $md5key;

    /**
     * @ORM\Column(type="smallint")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=21)
     */
    private $clientIp;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $notifyUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $failCnt;

    /**
     * @ORM\Column(type="integer")
     */
    private $sucCount;

    public function __construct()
    {
        $this->setFailCnt(0);
        $this->setSucCount(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): self
    {
        $this->appId = $appId;

        return $this;
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

    public function getMd5key(): ?string
    {
        return $this->md5key;
    }

    public function setMd5key(string $md5key): self
    {
        $this->md5key = $md5key;

        return $this;
    }

    public function getEnable(): ?int
    {
        return $this->enable;
    }

    public function setEnable(int $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(string $clientIp): self
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    public function getNotifyUrl(): ?string
    {
        return $this->notifyUrl;
    }

    public function setNotifyUrl(string $notifyUrl): self
    {
        $this->notifyUrl = $notifyUrl;

        return $this;
    }

    public function getFailCnt(): ?int
    {
        return $this->failCnt;
    }

    public function setFailCnt(int $failCnt): self
    {
        $this->failCnt = $failCnt;

        return $this;
    }

    public function getSucCount(): ?int
    {
        return $this->sucCount;
    }

    public function setSucCount(int $sucCount): self
    {
        $this->sucCount = $sucCount;

        return $this;
    }
}
