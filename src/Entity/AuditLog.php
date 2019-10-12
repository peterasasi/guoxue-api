<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuditLogRepository")
 */
class AuditLog extends BaseEntity
{
    // 身份认证审核日志
    const IdentityAuth = 'identity_auth';
    // 提现审核日志
    const WithdrawAuth = 'withdraw_auth';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $auditUid;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $auditNick;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $objectId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $objectType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuditUid(): ?int
    {
        return $this->auditUid;
    }

    public function setAuditUid(int $auditUid): self
    {
        $this->auditUid = $auditUid;

        return $this;
    }

    public function getAuditNick(): ?string
    {
        return $this->auditNick;
    }

    public function setAuditNick(string $auditNick): self
    {
        $this->auditNick = $auditNick;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function setObjectId(string $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function setObjectType(string $objectType): self
    {
        $this->objectType = $objectType;

        return $this;
    }
}
