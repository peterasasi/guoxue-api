<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="common_login_session")
 * @ORM\Entity(repositoryClass="App\Repository\LoginSessionRepository")
 */
class LoginSession extends BaseEntity
{

    /**
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @ORM\Column(name="login_session_id", type="string", length=64, unique=true)
     */
    private $loginSessionId;
    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;
    /**
     * @ORM\Column(name="login_info", type="text")
     */
    private $loginInfo;
    /**
     * @ORM\Column(name="expire_time", type="bigint")
     */
    private $expireTime;
    /**
     * @ORM\Column(name="create_time", type="bigint", nullable=false)
     */
    private $createTime;
    /**
     * @ORM\Column(name="update_time", type="bigint", nullable=false)
     */
    private $updateTime;
    /**
     * @ORM\Column(name="login_device_type", type="string", length=80, options={"default"="","comment"="登录设备类型"})
     */
    private $loginDeviceType;

    /**
     * @ORM\Column(type="string", length=80, options={"default"="","comment"="登录设备的唯一标识"})
     */
    private $deviceToken;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getLoginSessionId(): ?string
    {
        return $this->loginSessionId;
    }

    public function setLoginSessionId(string $loginSessionId): self
    {
        $this->loginSessionId = $loginSessionId;

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

    public function getLoginInfo(): ?string
    {
        return $this->loginInfo;
    }

    public function setLoginInfo(string $loginInfo): self
    {
        $this->loginInfo = $loginInfo;

        return $this;
    }

    public function getLoginDeviceType(): ?string
    {
        return $this->loginDeviceType;
    }

    public function setLoginDeviceType(string $loginDeviceType): self
    {
        $this->loginDeviceType = $loginDeviceType;

        return $this;
    }

    public function getExpireTime(): ?int
    {
        return $this->expireTime;
    }

    public function setExpireTime(int $expireTime): self
    {
        $this->expireTime = $expireTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getDeviceToken(): ?string
    {
        return $this->deviceToken;
    }

    public function setDeviceToken(string $deviceToken): self
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }
}
