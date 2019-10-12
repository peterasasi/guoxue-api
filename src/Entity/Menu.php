<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_menu")
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 */
class Menu extends BaseEntity
{

    const BackendMenu = "backend";

    const FrontMenu = "front";

    /**
     * @ORM\ManyToMany(targetEntity="AuthRole", mappedBy="menus")
     */
    private $roles;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=32)
     */
    private $icon;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;
    /**
     * @ORM\Column(type="bigint")
     */
    private $pid;
    /**
     * @ORM\Column(type="bigint")
     */
    private $level;
    /**
     * @ORM\Column(type="integer")
     */
    private $sort;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;
    /**
     * @ORM\Column(type="smallint")
     */
    private $urlType;
    /**
     * @ORM\Column(type="smallint")
     */
    private $hide;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tip;
    /**
     * @ORM\Column(type="integer")
     */
    private $status;
    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;
    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $scene;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
    }

    public function removeRole(AuthRole $role) {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }

    public function addRole(AuthRole $role) {
        $this->roles->add($role);
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * @param mixed $urlType
     */
    public function setUrlType($urlType)
    {
        $this->urlType = $urlType;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Menu
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHide(): ?int
    {
        return $this->hide;
    }

    public function setHide(int $hide): self
    {
        $this->hide = $hide;

        return $this;
    }

    public function getTip(): ?string
    {
        return $this->tip;
    }

    /**
     * @param string $tip
     * @return Menu
     */
    public function setTip(string $tip): self
    {
        $this->tip = $tip;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Menu
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getScene(): ?string
    {
        return $this->scene;
    }

    public function setScene(string $scene): self
    {
        $this->scene = $scene;

        return $this;
    }
}
