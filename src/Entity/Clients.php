<?php

namespace App\Entity;

use Dbh\SfCoreBundle\Common\ClientsInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table(name="common_clients", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_80D67F2C19EB6921", columns={"client_id"})})
 * @ORM\Entity
 */
class Clients extends BaseEntity implements ClientsInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string", length=64, nullable=false)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=32, nullable=false)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=64, nullable=false)
     */
    private $clientSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="api_alg", type="string", length=16, nullable=false)
     */
    private $apiAlg;

    /**
     * @var string
     *
     * @ORM\Column(name="project_id", type="string", length=72, nullable=false)
     */
    private $projectId;

    /**
     * @var int
     *
     * @ORM\Column(name="create_time", type="bigint", nullable=false)
     */
    private $createTime;

    /**
     * @var int
     *
     * @ORM\Column(name="update_time", type="bigint", nullable=false)
     */
    private $updateTime;

    /**
     * @ORM\Column(name="total_limit", type="integer", type="bigint", nullable=false, options={"unsigned"=true, "default"="0","comment"="总请求次数"})
     */
    private $totalLimit;

    /**
     * @ORM\Column(name="day_limit", type="integer", type="bigint", nullable=false, options={"unsigned"=true,"default"="0","comment"="每日请求次数"})
     */
    private $dayLimit;

    /**
     * @ORM\Column(type="text")
     */
    private $userPrivateKey;

    /**
     * @ORM\Column(type="text")
     */
    private $userPublicKey;

    /**
     * @ORM\Column(type="text")
     */
    private $sysPublicKey;

    /**
     * @ORM\Column(type="text")
     */
    private $sysPrivateKey;

    public function toArrayData() {
        return [
            'id' => $this->getId(),
            'client_secret' => $this->getClientSecret(),
            'client_id' => $this->getClientId(),
            'uid' => $this->getUid(),
            'create_time' => $this->getCreateTime(),
            'update_time' => $this->getUpdateTime(),
            'day_limit' => $this->getDayLimit(),
            'total_limit' => $this->getTotalLimit(),
            'client_name' => $this->getClientName(),
            'project_id' => $this->getProjectId(),
            'api_alg' => $this->getApiAlg(),
            'user_private_key' => $this->getUserPrivateKey(),
            'user_public_key' => $this->getUserPublicKey(),
            'sys_private_key' => $this->getSysPrivateKey(),
            'sys_public_key' => $this->getSysPublicKey()
        ];
    }

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
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
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

    public function getDayLimit(): int
    {
        return $this->dayLimit;
    }

    public function setDayLimit(int $dayLimit): ClientsInterface
    {
        $this->dayLimit = $dayLimit;
        return $this;
    }

    public function getTotalLimit(): ?int
    {
        return $this->totalLimit;
    }

    public function setTotalLimit(int $totalLimit): ClientsInterface
    {
        $this->totalLimit = $totalLimit;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     */
    public function setClientName(string $clientName): void
    {
        $this->clientName = $clientName;
    }

    /**
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * @param string $projectId
     */
    public function setProjectId(string $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return string
     */
    public function getApiAlg(): string
    {
        return $this->apiAlg;
    }

    /**
     * @param string $apiAlg
     */
    public function setApiAlg(string $apiAlg): void
    {
        $this->apiAlg = $apiAlg;
    }

    public function setArrayData(array $data) {
        if (is_array($data)) {
            array_key_exists('id', $data) && $this->setId($data['id']);
            array_key_exists('client_secret', $data) && $this->setClientSecret($data['client_secret']);
            array_key_exists('client_id', $data) && $this->setClientId($data['client_id']);
            array_key_exists('uid', $data) && $this->setUid($data['uid']);
            array_key_exists('create_time', $data) && $this->setCreateTime($data['create_time']);
            array_key_exists('update_time', $data) && $this->setUpdateTime($data['update_time']);
            array_key_exists('day_limit', $data) && $this->setDayLimit($data['day_limit']);
            array_key_exists('total_limit', $data) && $this->setTotalLimit($data['total_limit']);
            array_key_exists('client_name', $data) && $this->setClientName($data['client_name']);
            array_key_exists('project_id', $data) && $this->setProjectId($data['project_id']);
            array_key_exists('api_alg', $data) && $this->setApiAlg($data['api_alg']);
            array_key_exists('user_private_key', $data) && $this->setUserPrivateKey($data['user_private_key']);
            array_key_exists('user_public_key', $data) && $this->setUserPublicKey($data['user_public_key']);
            array_key_exists('sys_private_key', $data) && $this->setSysPrivateKey($data['sys_private_key']);
            array_key_exists('sys_public_key', $data) && $this->setSysPublicKey($data['sys_public_key']);

        }
    }

    public function getUserPrivateKey(): ?string
    {
        return $this->userPrivateKey;
    }

    public function setUserPrivateKey(string $userPrivateKey): ClientsInterface
    {
        $this->userPrivateKey = $userPrivateKey;

        return $this;
    }

    public function getUserPublicKey(): ?string
    {
        return $this->userPublicKey;
    }

    public function setUserPublicKey(string $userPublicKey): ClientsInterface
    {
        $this->userPublicKey = $userPublicKey;

        return $this;
    }

    public function getSysPublicKey(): ?string
    {
        return $this->sysPublicKey;
    }

    public function setSysPublicKey(string $sysPublicKey): ClientsInterface
    {
        $this->sysPublicKey = $sysPublicKey;

        return $this;
    }

    public function getSysPrivateKey(): ?string
    {
        return $this->sysPrivateKey;
    }

    public function setSysPrivateKey(string $sysPrivateKey): ClientsInterface
    {
        $this->sysPrivateKey = $sysPrivateKey;

        return $this;
    }
}
