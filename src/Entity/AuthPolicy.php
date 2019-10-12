<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="auth_policy", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_80D67F2C19EB6921", columns={"ver", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\AuthPolicyRepository")
 */
class AuthPolicy extends BaseEntity
{



    /**
     * @ORM\ManyToMany(targetEntity="AuthRole", mappedBy="policies")
     */
    protected $roles;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="bigint")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $ver;
    /**
     * @ORM\Column(type="text")
     */
    private $statements;
    /**
     * @ORM\Column(name="create_time", type="bigint")
     */
    private $createTime;
    /**
     * @ORM\Column(name="update_time", type="bigint")
     */
    private $updateTime;
    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $note;
    /**
     * @ORM\Column(type="string", length=12)
     */
    private $cate;
    /**
     * @ORM\Column(type="integer")
     */
    private $isDefaultVersion;

    public function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
    }

    public function addRole(AuthRole $role) {
        $this->roles->add($role);
    }

    public function removeRole(AuthRole $role) {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisDefaultVersion()
    {
        return $this->isDefaultVersion;
    }

    /**
     * @param mixed $isDefaultVersion
     */
    public function setIsDefaultVersion($isDefaultVersion): void
    {
        $this->isDefaultVersion = $isDefaultVersion;
    }

    /**
     * @return mixed
     */
    public function getCate()
    {
        return $this->cate;
    }

    /**
     * @param mixed $cate
     */
    public function setCate($cate): void
    {
        $this->cate = $cate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVer(): ?int
    {
        return $this->ver;
    }

    public function setVer(int $ver): self
    {
        $this->ver = $ver;

        return $this;
    }

    public function getStatements(): ?string
    {
        return $this->statements;
    }

    public function setStatements(string $statements): self
    {
        $this->statements = $statements;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
