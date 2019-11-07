<?php

namespace App\Entity;

use Dbh\SfCoreBundle\Common\UserAccountInterface;
use Dbh\SfCoreBundle\Common\UserProfileInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserAccount
 *
 * @ORM\Table(name="user_account", uniqueConstraints={@ORM\UniqueConstraint(name="app_id", columns={"project_id", "username"})})
 * @ORM\Entity
 */
class UserAccount extends BaseEntity implements UserInterface, UserAccountInterface
{

    /**
     * @ORM\ManyToMany(targetEntity="AuthRole")
     * @ORM\JoinTable(name="auth_user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;
    /**
     * @ORM\OneToOne(targetEntity="UserProfile", mappedBy="user")
     */
    private $profile;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="project_id", type="string", length=32, nullable=false, options={"comment"="项目id"})
     */
    private $projectId = '';
    /**
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]{1}([a-zA-Z0-9_]){5,24}$/i",
     *     match=true,
     *     message="username length must between 6,16;the first char must be a alpha."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=128, nullable=false, options={"fixed"=true,"comment"="用户名"})
     */
    private $username = '';
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9_!@#$%^&*()_+-=\[\]{}|;:,.<>]{8,24}$/i",
     *     match=true,
     *     message="password length must between 8,24 and can only be used in alphanumeric and special characters(_!@#$%^&*()_+-=[]{}|;:,.<>)."
     * )
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false, options={"fixed"=true,"comment"="密码"})
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="pay_secret", type="string", length=32, nullable=false, options={"default"="","comment"="支付密码"})
     */
    private $paySecret = '';
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=16, nullable=false, options={"default"="123456","fixed"=true,"comment"="加盐密码"})
     */
    private $salt = '123456';
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[_0-9]{5,32}$/i",
     *     match=true,
     *     message="mobile can only be used in number"
     * )
     *
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=48, nullable=false, options={"fixed"=true,"comment"="用户手机"})
     */
    private $mobile = '';
    /**
     * @var string
     *
     * @ORM\Column(name="country_no", type="string", length=10, nullable=false, options={"default"="+86","comment"="国家电话代码"})
     */
    private $countryNo = '+86';
    /**
     *
     * @Assert\Regex(
     *     pattern="/^.{0,64}$/i",
     *     match=true,
     *     message="email length must between 3,64"
     * )
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=false, options={"fixed"=true,"comment"="邮箱"})
     */
    private $email = '';
    /**
     * @var string
     *
     * @ORM\Column(name="wxapp_openid", type="string", length=64, nullable=false, options={"default"="","comment"="微信app"})
     */
    private $wxappOpenid = '';
    /**
     * @var string
     *
     * @ORM\Column(name="wx_openid", type="string", length=64, nullable=false, options={"default"="","comment"="微信公众号"})
     */
    private $wxOpenid = '';
    /**
     * @var string
     *
     * @ORM\Column(name="wx_unionid", type="string", length=64, nullable=false, options={"default"="","comment"="微信unionid"})
     */
    private $wxUnionid = '';
    /**
     * @var string
     *
     * @ORM\Column(name="qq_openid", type="string", length=64, nullable=false, options={"default"="","comment"="qq"})
     */
    private $qqOpenid = '';
    /**
     * @var string
     *
     * @ORM\Column(name="weibo_openid", type="string", length=64, nullable=false, options={"default"="","comment"="微博"})
     */
    private $weiboOpenid = '';
    /**
     * @var int
     *
     * @ORM\Column(name="create_time", type="bigint", nullable=false, options={"default"="0","unsigned"=true,"comment"="注册时间"})
     */
    private $createTime = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="reg_ip", type="bigint", nullable=false, options={"default"="0","comment"="注册IP"})
     */
    private $regIp = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="last_login_time", type="bigint", nullable=false, options={"default"="0","unsigned"=true,"comment"="最后登录时间"})
     */
    private $lastLoginTime = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="last_login_ip", type="bigint", nullable=false, options={"default"="0","comment"="最后登录IP"})
     */
    private $lastLoginIp = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="update_time", type="bigint", nullable=false, options={"default"="0","unsigned"=true,"comment"="更新时间"})
     */
    private $updateTime = '0';
    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"default"="1","comment"="用户状态"})
     */
    private $status = '1';
    /**
     * @var int
     *
     * @ORM\Column(name="login_device_cnt", type="integer", nullable=false, options={"default"="3","comment"="同时可登录设备数量"})
     */
    private $loginDeviceCnt = '3';
    /**
     * @var bool
     *
     * @ORM\Column(name="mobile_auth", type="boolean", nullable=false, options={"default"="0","comment"="手机号是否已认证"})
     */
    private $mobileAuth = '0';
    /**
     * @var bool
     *
     * @ORM\Column(name="email_auth", type="boolean", nullable=false, options={"default"="0","comment"="邮箱是否已认证"})
     */
    private $emailAuth = '0';
    /**
     * @ORM\Column(name="password_set", type="integer", nullable=false, options={"default"="0","comment"="密码是否已设置(部分是自动生成的密码)"})
     */
    private $passwordSet;

    /**
     * @ORM\Column(type="string", length=32, options={"default"="","comment"="谷歌令牌码"})
     */
    private $googleSecret;

    public function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
        $this->setPasswordSet(0);
        $this->setGoogleSecret('');
    }

    /**
     * @return bool
     */
    public function isEmailAuth(): bool
    {
        return $this->emailAuth;
    }

    /**
     * @param bool $emailAuth
     */
    public function setEmailAuth(bool $emailAuth): void
    {
        $this->emailAuth = $emailAuth;
    }

    /**
     * @return ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function removeRole(AuthRole $role)
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }

    public function addRole(AuthRole $role)
    {
        $this->roles->add($role);
    }

    /**
     * @return UserProfile
     */
    public function getProfile(): UserProfileInterface
    {
        return $this->profile;
    }

    /**
     * @param UserProfile $profile
     * @return UserAccount
     */
    public function setProfile($profile): self
    {
        $this->profile = $profile;
        return $this;
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPaySecret(): string
    {
        return $this->paySecret;
    }

    /**
     * @param string $paySecret
     */
    public function setPaySecret(string $paySecret): void
    {
        $this->paySecret = $paySecret;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getCountryNo(): string
    {
        return $this->countryNo;
    }

    /**
     * @param string $countryNo
     */
    public function setCountryNo(string $countryNo): void
    {
        $this->countryNo = $countryNo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getWxappOpenid(): string
    {
        return $this->wxappOpenid;
    }

    /**
     * @param string $wxappOpenid
     */
    public function setWxappOpenid(string $wxappOpenid): void
    {
        $this->wxappOpenid = $wxappOpenid;
    }

    /**
     * @return string
     */
    public function getWxOpenid(): string
    {
        return $this->wxOpenid;
    }

    /**
     * @param string $wxOpenid
     */
    public function setWxOpenid(string $wxOpenid): void
    {
        $this->wxOpenid = $wxOpenid;
    }

    /**
     * @return string
     */
    public function getWxUnionid(): string
    {
        return $this->wxUnionid;
    }

    /**
     * @param string $wxUnionid
     */
    public function setWxUnionid(string $wxUnionid): void
    {
        $this->wxUnionid = $wxUnionid;
    }

    /**
     * @return string
     */
    public function getQqOpenid(): string
    {
        return $this->qqOpenid;
    }

    /**
     * @param string $qqOpenid
     */
    public function setQqOpenid(string $qqOpenid): void
    {
        $this->qqOpenid = $qqOpenid;
    }

    /**
     * @return string
     */
    public function getWeiboOpenid(): string
    {
        return $this->weiboOpenid;
    }

    /**
     * @param string $weiboOpenid
     */
    public function setWeiboOpenid(string $weiboOpenid): void
    {
        $this->weiboOpenid = $weiboOpenid;
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
    public function getRegIp(): int
    {
        return $this->regIp;
    }

    /**
     * @param int $regIp
     */
    public function setRegIp(int $regIp): void
    {
        $this->regIp = $regIp;
    }

    /**
     * @return int
     */
    public function getLastLoginTime(): int
    {
        return $this->lastLoginTime;
    }

    /**
     * @param int $lastLoginTime
     */
    public function setLastLoginTime(int $lastLoginTime): void
    {
        $this->lastLoginTime = $lastLoginTime;
    }

    /**
     * @return int
     */
    public function getLastLoginIp(): int
    {
        return $this->lastLoginIp;
    }

    /**
     * @param int $lastLoginIp
     */
    public function setLastLoginIp(int $lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
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

    /**
     * @return integer
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getLoginDeviceCnt(): int
    {
        return $this->loginDeviceCnt;
    }

    /**
     * @param int $loginDeviceCnt
     */
    public function setLoginDeviceCnt(int $loginDeviceCnt): void
    {
        $this->loginDeviceCnt = $loginDeviceCnt;
    }

    /**
     * @return bool
     */
    public function isMobileAuth(): bool
    {
        return $this->mobileAuth;
    }

    /**
     * @param bool $mobileAuth
     */
    public function setMobileAuth(bool $mobileAuth): void
    {
        $this->mobileAuth = $mobileAuth;
    }

    public function eraseCredentials()
    {

    }

    public function getPasswordSet(): ?int
    {
        return $this->passwordSet;
    }

    public function setPasswordSet(int $passwordSet): self
    {
        $this->passwordSet = $passwordSet;

        return $this;
    }

    public function getGoogleSecret(): ?string
    {
        return $this->googleSecret;
    }

    public function setGoogleSecret(string $googleSecret): self
    {
        $this->googleSecret = $googleSecret;

        return $this;
    }
}
