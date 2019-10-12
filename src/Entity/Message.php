<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_message")
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $projectId;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $dtreeType;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

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
    private $sendTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $fromUid;

    /**
     * @ORM\Column(type="bigint")
     */
    private $toUid;

    /**
     * @ORM\Column(type="smallint")
     */
    private $isDelivery;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $summary;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extra;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getDtreeType(): ?string
    {
        return $this->dtreeType;
    }

    public function setDtreeType(string $dtreeType): self
    {
        $this->dtreeType = $dtreeType;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getSendTime(): ?int
    {
        return $this->sendTime;
    }

    public function setSendTime(int $sendTime): self
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    public function getFromUid(): ?int
    {
        return $this->fromUid;
    }

    public function setFromUid(int $fromUid): self
    {
        $this->fromUid = $fromUid;

        return $this;
    }

    public function getToUid(): ?int
    {
        return $this->toUid;
    }

    public function setToUid(int $toUid): self
    {
        $this->toUid = $toUid;

        return $this;
    }

    public function getIsDelivery(): ?int
    {
        return $this->isDelivery;
    }

    public function setIsDelivery(int $isDelivery): self
    {
        $this->isDelivery = $isDelivery;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Message
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getExtra(): ?string
    {
        return $this->extra;
    }

    /**
     * @param string|null $extra
     * @return Message
     */
    public function setExtra(?string $extra): self
    {
        $this->extra = $extra;

        return $this;
    }
}
