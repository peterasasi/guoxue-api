<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_message_box")
 * @ORM\Entity(repositoryClass="App\Repository\MessageBoxRepository")
 */
class MessageBox extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="bigint")
     */
    private $msgId;

    /**
     * @ORM\Column(type="smallint")
     */
    private $msgStatus;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMsgId(): ?int
    {
        return $this->msgId;
    }

    public function setMsgId(int $msgId): self
    {
        $this->msgId = $msgId;

        return $this;
    }

    public function getMsgStatus(): ?int
    {
        return $this->msgStatus;
    }

    /**
     * @param int $msgStatus
     * @return MessageBox
     */
    public function setMsgStatus(int $msgStatus): self
    {
        $this->msgStatus = $msgStatus;

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
}
