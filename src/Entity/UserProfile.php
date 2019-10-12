<?php

namespace App\Entity;

use Dbh\SfCoreBundle\Common\UserProfileInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserProfile
 *
 * @ORM\Table(name="user_profile")
 * @ORM\Entity
 */
class UserProfile implements UserProfileInterface
{

    /**
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="UserAccount", inversedBy="profile")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;
    /**
     * @var string
     *
     * @ORM\Column(name="geohash" ,type="string", length=64, nullable=false, options={"default"="","comment"="所在经纬度geohash"})
     */
    private $geohash = '';
    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=64, nullable=false, options={"default"="","fixed"=true,"comment"="昵称"})
     */
    private $nickname = '';
    /**
     * @var bool
     *
     * @ORM\Column(name="sex", type="boolean", nullable=false, options={"default"="0","comment"="性别"})
     */
    private $sex = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="birthday", type="integer", nullable=false, options={"default"="0","comment"="生日"})
     */
    private $birthday = '0';
    /**
     * @var string
     *
     * @ORM\Column(name="head", type="string", length=500, nullable=false, options={"default"="0","comment"="头像"})
     */
    private $head = '';
    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false, options={"default"="0","comment"="用户积分"})
     */
    private $score = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="login", type="integer", nullable=false, options={"default"="0","unsigned"=true,"comment"="登录次数"})
     */
    private $login = '0';
    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1","comment"="会员状态"})
     */
    private $status = '1';
    /**
     * @var string
     *
     * @ORM\Column(name="sign", type="string", length=256, nullable=false, options={"default"="","comment"="用户个性签名"})
     */
    private $sign = '';
    /**
     * @var int|null
     *
     * @ORM\Column(name="bg_img", type="integer", nullable=true, options={"default"="0","unsigned"=true,"comment"="空间背景图片"})
     */
    private $bgImg = '0';
    /**
     * @var bool
     *
     * @ORM\Column(name="email_validate", type="boolean", nullable=false, options={"default"="0","comment"="邮箱认证标记"})
     */
    private $emailValidate = '0';
    /**
     * @var bool
     *
     * @ORM\Column(name="identity_validate", type="boolean", nullable=false, options={"default"="0","comment"="实名认证标记"})
     */
    private $identityValidate = '0';
    /**
     * @var string
     *
     * @ORM\Column(name="idcode", type="string", length=16, nullable=false, options={"default"="","fixed"=true,"comment"="推荐码"})
     */
    private $idcode = '';
    /**
     * @var int
     *
     * @ORM\Column(name="default_address", type="bigint", nullable=false, options={"default"="0","comment"="用户默认收货地址"})
     */
    private $defaultAddress = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="invite_uid", type="bigint", nullable=false, options={"comment"="推荐人UID"})
     */
    private $inviteUid = '0';
    /**
     * @var int
     *
     * @ORM\Column(name="exp", type="bigint", nullable=false, options={"default"="0","comment"="用户经验值"})
     */
    private $exp = '0';
    /**
     * @var string
     *
     * @ORM\Column(name="nation", type="string", length=32, nullable=false, options={"default"="","comment"="民族"})
     */
    private $nation = '';
    /**
     * @var bool
     *
     * @ORM\Column(name="online_status", type="boolean", nullable=false, options={"default"="1","comment"="在线状态"})
     */
    private $onlineStatus = '1';
    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=128, nullable=false, options={"default"="","comment"="真实姓名"})
     */
    private $realname = '';

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getGeohash(): string
    {
        return $this->geohash;
    }

    /**
     * @param string $geohash
     */
    public function setGeohash(string $geohash): void
    {
        $this->geohash = $geohash;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return bool
     */
    public function isSex(): bool
    {
        return $this->sex;
    }

    /**
     * @param bool $sex
     */
    public function setSex(bool $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return int
     */
    public function getBirthday(): int
    {
        return $this->birthday;
    }

    /**
     * @param int $birthday
     */
    public function setBirthday(int $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getHead(): string
    {
        return $this->head;
    }

    /**
     * @param string $head
     */
    public function setHead(string $head): void
    {
        $this->head = $head;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return int
     */
    public function getLogin(): int
    {
        return $this->login;
    }

    /**
     * @param int $login
     */
    public function setLogin(int $login): void
    {
        $this->login = $login;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @return int|null
     */
    public function getBgImg(): ?int
    {
        return $this->bgImg;
    }

    /**
     * @param int|null $bgImg
     */
    public function setBgImg(?int $bgImg): void
    {
        $this->bgImg = $bgImg;
    }

    /**
     * @return bool
     */
    public function isEmailValidate(): bool
    {
        return $this->emailValidate;
    }

    /**
     * @param bool $emailValidate
     */
    public function setEmailValidate(bool $emailValidate): void
    {
        $this->emailValidate = $emailValidate;
    }

    /**
     * @return bool
     */
    public function isIdentityValidate(): bool
    {
        return $this->identityValidate;
    }

    /**
     * @param bool $identityValidate
     */
    public function setIdentityValidate(bool $identityValidate): void
    {
        $this->identityValidate = $identityValidate;
    }

    /**
     * @return string
     */
    public function getIdcode(): string
    {
        return $this->idcode;
    }

    /**
     * @param string $idcode
     */
    public function setIdcode(string $idcode): void
    {
        $this->idcode = $idcode;
    }

    /**
     * @return int
     */
    public function getDefaultAddress(): int
    {
        return $this->defaultAddress;
    }

    /**
     * @param int $defaultAddress
     */
    public function setDefaultAddress(int $defaultAddress): void
    {
        $this->defaultAddress = $defaultAddress;
    }

    /**
     * @return int
     */
    public function getInviteUid(): int
    {
        return $this->inviteUid;
    }

    /**
     * @param int $inviteUid
     */
    public function setInviteUid(int $inviteUid): void
    {
        $this->inviteUid = $inviteUid;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }

    /**
     * @return string
     */
    public function getNation(): string
    {
        return $this->nation;
    }

    /**
     * @param string $nation
     */
    public function setNation(string $nation): void
    {
        $this->nation = $nation;
    }

    /**
     * @return bool
     */
    public function isOnlineStatus(): bool
    {
        return $this->onlineStatus;
    }

    /**
     * @param bool $onlineStatus
     */
    public function setOnlineStatus(bool $onlineStatus): void
    {
        $this->onlineStatus = $onlineStatus;
    }

    /**
     * @return string
     */
    public function getRealname(): string
    {
        return $this->realname;
    }

    /**
     * @param string $realname
     */
    public function setRealname(string $realname): void
    {
        $this->realname = $realname;
    }

    public function getUid() {
        if ($this->user instanceof UserAccount) {
            return $this->user->getId();
        }
        return 0;
    }
    public function getMobile() {
        if ($this->user instanceof UserAccount) {
            return $this->user->getMobile();
        }
        return '';
    }
}
