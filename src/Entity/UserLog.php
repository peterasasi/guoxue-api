<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLog
 *
 * @ORM\Table(name="user_log")
 * @ORM\Entity
 */
class UserLog extends BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false)
     */
    private $uid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="log_type", type="string", length=32, nullable=false)
     */
    private $logType = '';

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=16, nullable=false, options={"fixed"=true})
     */
    private $ip = '';

    /**
     * @var string
     *
     * @ORM\Column(name="ua", type="string", length=512, nullable=false)
     */
    private $ua = '';

    /**
     * @var string
     *
     * @ORM\Column(name="device_type", type="string", length=32, nullable=false, options={"fixed"=true})
     */
    private $deviceType = '';

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=512, nullable=false)
     */
    private $note = '';

    /**
     * @var int
     *
     * @ORM\Column(name="create_time", type="bigint", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="update_time", type="bigint", nullable=false)
     */
    private $updateTime = '0';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getLogType(): string
    {
        return $this->logType;
    }

    /**
     * @param string $logType
     */
    public function setLogType(string $logType): void
    {
        $this->logType = $logType;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getUa(): string
    {
        return $this->ua;
    }

    /**
     * @param string $ua
     */
    public function setUa(string $ua): void
    {
        $this->ua = $ua;
    }

    /**
     * @return string
     */
    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    /**
     * @param string $deviceType
     */
    public function setDeviceType(string $deviceType): void
    {
        $this->deviceType = $deviceType;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getUpdateTime(): int
    {
        return $this->updateTime;
    }

    /**
     * @param int $updateTime
     */
    public function setUpdateTime(int $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
