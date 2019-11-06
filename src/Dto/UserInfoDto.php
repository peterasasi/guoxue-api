<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/21
 * Time: 17:54
 */

namespace App\Dto;


use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Entity\UserProfile;
use App\Entity\UserWallet;

class UserInfoDto
{
    // 用户核心信息
    protected $vipLevel;
    protected $id;
    protected $username;
    protected $mobile;
    protected $countryNo;
    protected $lastLoginTime;
    protected $lastLoginIp;
    protected $mobileAuth;
//    protected $loginDeviceCnt;
    // 用户其它资料
    protected $nickname;
//    protected $sex;
//    protected $score;
//    protected $birthday;
    protected $avatar;
//    protected $sign;
//    protected $bgImg;
    protected $idcode;
//    protected $inviteUid;
//    protected $exp;
//    protected $realname;
    protected $idValidate;
    // 会话id
//    protected $sid;
    // 会员等级id
//    protected $gradeId;
    // 密码是否已设置
    protected $pwdIsSet;
    // 邮箱
//    protected $email;
//    protected $googleAuthSwitch;

    protected $balance;
    protected $inviteCnt;
    protected $frozenWithdraw;


    public function setProfitGraph(ProfitGraph $profitGraph) {
        $this->vipLevel = $profitGraph->getVipLevel();
        $this->inviteCnt = $profitGraph->getInviteCount();
    }

    public function setWallet(UserWallet $userWallet) {
        $this->balance = $userWallet->getBalance();
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->setId($userAccount->getId());
        $this->setCountryNo($userAccount->getCountryNo());
        $this->setLastLoginIp($userAccount->getLastLoginIp());
        $this->setLastLoginTime($userAccount->getLastLoginTime());
        $this->setMobile($this->hideMobile($userAccount->getMobile()));
        $this->setUsername($userAccount->getUsername());
        $this->setMobileAuth($userAccount->isMobileAuth());
        $this->setUserProfile($userAccount->getProfile());
        $this->setPwdIsSet($userAccount->getPasswordSet());
        $this->setFrozenWithdraw($userAccount->getProfile()->getFrozenWithdraw());
    }

    /**
     * @return mixed
     */
    public function getFrozenWithdraw()
    {
        return $this->frozenWithdraw;
    }

    /**
     * @param mixed $frozenWithdraw
     */
    public function setFrozenWithdraw($frozenWithdraw): void
    {
        $this->frozenWithdraw = $frozenWithdraw;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getVipLevel()
    {
        return $this->vipLevel;
    }

    /**
     * @param mixed $vipLevel
     */
    public function setVipLevel($vipLevel): void
    {
        $this->vipLevel = $vipLevel;
    }

    /**
     * @return mixed
     */
    public function getPwdIsSet()
    {
        return $this->pwdIsSet;
    }

    /**
     * @param mixed $pwdIsSet
     */
    public function setPwdIsSet($pwdIsSet): void
    {
        $this->pwdIsSet = $pwdIsSet;
    }

    public function hideMobile($mobile) {
//        if (strlen($mobile) > 7) {
//            return substr($mobile, 0, 2). '****'.substr($mobile, -4);
//        }
        return $mobile;
    }

    public function setUserProfile(UserProfile $userProfile)
    {
        if (!$userProfile) {
            return;
        }
        $this->setNickname($userProfile->getNickname());
        $this->setAvatar($userProfile->getHead());
        $this->setIdcode($userProfile->getIdcode());
        $this->setIdValidate($userProfile->isIdentityValidate() ? 1 : 0);
    }

    /**
     * @return mixed
     */
    public function getIdValidate()
    {
        return $this->idValidate;
    }

    /**
     * @param mixed $idValidate
     */
    public function setIdValidate($idValidate): void
    {
        $this->idValidate = $idValidate;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getIdcode()
    {
        return $this->idcode;
    }

    /**
     * @param mixed $idcode
     */
    public function setIdcode($idcode): void
    {
        $this->idcode = $idcode;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getCountryNo()
    {
        return $this->countryNo;
    }

    /**
     * @param mixed $countryNo
     */
    public function setCountryNo($countryNo): void
    {
        $this->countryNo = $countryNo;
    }

    /**
     * @return mixed
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * @param mixed $lastLoginTime
     */
    public function setLastLoginTime($lastLoginTime): void
    {
        $this->lastLoginTime = $lastLoginTime;
    }

    /**
     * @return mixed
     */
    public function getLastLoginIp()
    {
        return long2ip($this->lastLoginIp);
    }

    /**
     * @param mixed $lastLoginIp
     */
    public function setLastLoginIp($lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
    }

    /**
     * @return mixed
     */
    public function getMobileAuth()
    {
        return $this->mobileAuth;
    }

    /**
     * @param mixed $mobileAuth
     */
    public function setMobileAuth($mobileAuth): void
    {
        $this->mobileAuth = $mobileAuth;
    }
}
