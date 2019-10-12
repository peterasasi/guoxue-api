<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="auth_role", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_key", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\AuthRoleRepository")
 */
class AuthRole extends BaseEntity
{

    /**
     * Many Roles have Many Menus.
     * @ORM\ManyToMany(targetEntity="Menu")
     * @ORM\JoinTable(name="auth_role_menu",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="menu_id", referencedColumnName="id")}
     *      )
     **/
    public $menus;
    /**
     * @ORM\ManyToMany(targetEntity="UserAccount", mappedBy="roles")
     */
    public $users;
    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="AuthPolicy")
     * @ORM\JoinTable(name="auth_policy_role",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="policy_id", referencedColumnName="id")}
     *      )
     **/
    private $policies;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="bigint")
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9\.@\-]{6,64}$/i",
     *     match=true,
     *     message="role name invalid"
     * )
     * @ORM\Column(type="string", length=64)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    public function __construct()
    {
        parent::__construct();
        $this->policies = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     */
    public function setEnable($enable): void
    {
        $this->enable = $enable;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
        }
        return $this;
    }

    public function addMenu(Menu $menu) {
        if (!$this->menus->contains($menu)) {
            $menu->addRole($this);
            $this->menus->add($menu);
        }
        return $this;
    }

    public function removeUser(UserAccount $userAccount): self
    {
        if ($this->users->contains($userAccount)) {
            $this->users->removeElement($userAccount);
        }
        return $this;
    }

    public function addUser(UserAccount $userAccount) {
        $userAccount->addRole($this);
//        $this->users->add($userAccount);
        return $this;
    }

    public function removePolicy(AuthPolicy $authPolicy): self
    {
        if ($this->policies->contains($authPolicy)) {
            $this->policies->removeElement($authPolicy);
        }
        return $this;
    }

    public function addPolicy(AuthPolicy $authPolicy) {
        if (!$this->policies->contains($authPolicy)) {
            $authPolicy->addRole($this);
            $this->policies->add($authPolicy);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPolicies()
    {
        return $this->policies;
    }

    /**
     * @param mixed $policies
     */
    public function setPolicies($policies): void
    {
        $this->policies = $policies;
    }

    public function getId(): ?int
    {
        return $this->id;
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
