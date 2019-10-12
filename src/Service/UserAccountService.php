<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 11:02
 */

namespace App\Service;


use App\Entity\UserAccount;
use App\Entity\UserProfile;
use App\Repository\UserAccountRepository;
use App\Repository\UserProfileRepository;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserAccountInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserProfileInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserAccountService extends BaseService implements UserAccountServiceInterface
{
    /**
     * @var UserAccountRepository
     */
    protected $userAccountRepo;

    /**
     * @var UserProfileRepository
     */
    protected $userProfileRepo;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $pwdEncoder;

    /**
     * @var UserAccountRepository
     */
    protected $repo;

    public function __construct(UserProfileRepository $userProfileRepository, UserPasswordEncoderInterface $encoder, UserAccountRepository $userAccountRepository)
    {
        $this->userProfileRepo = $userProfileRepository;
        $this->userAccountRepo = $userAccountRepository;
        $this->pwdEncoder = $encoder;
        $this->repo = $userAccountRepository;
    }

    /**
     * @param $mobile
     * @param $ip
     * @param $projectId
     * @param string $countryNo
     * @return \by\infrastructure\base\CallResult
     */
    function getUserOrCreate($mobile, $ip, $projectId, $countryNo = '+86') {
        $userAccount = $this->userAccountRepo->findOneBy(['mobile' => $mobile]);
        if (!($userAccount instanceof UserAccount)) {
            $userAccount = new UserAccount();
            $userAccount->setStatus(StatusEnum::ENABLE);
            $userAccount->setMobile($mobile);
            $userAccount->setCountryNo($countryNo);
            $userAccount->setMobile($mobile);
            $username = 'm'.trim($countryNo, "+").$mobile;
            $userAccount->setUsername($username);
            if (empty($password)) $password = substr(md5($mobile.time()),0, 16);
            $userAccount->setPassword($password);
            $userAccount->setRegIp(ip2long($ip));
            $userAccount->setProjectId($projectId);
            $userAccount->setLastLoginTime(time());
            $userAccount->setMobileAuth(true);
            if (!empty($password)) {
                $userAccount->setPasswordSet(1);
            }
            $userProfile = new UserProfile();
            $userProfile->setNickname('手机用户'.time());
            return $this->create($userAccount, $userProfile);
        }

        return CallResultHelper::success($userAccount);
    }

    function create(UserAccountInterface $userAccount, UserProfileInterface $userProfile)
    {
        if (!$userAccount instanceof UserAccount) {
            return CallResultHelper::fail('userAccount 参数错误');
        }
        if (!$userProfile instanceof UserProfile) {
            return CallResultHelper::fail('UserProfile 参数错误');
        }
        $userAccount->setSalt(StringHelper::randAlphabet(8));
        $encrypted = $this->pwdEncoder->encodePassword($userAccount, $userAccount->getPassword());
        $userAccount->setPassword($encrypted);

        $this->userAccountRepo->getEntityManager()->beginTransaction();
        try {
            $userProfile->setUser($userAccount);
            $this->userAccountRepo->getEntityManager()->persist($userAccount);
            $this->userProfileRepo->getEntityManager()->persist($userProfile);
            $this->userAccountRepo->getEntityManager()->flush();
            $this->userAccountRepo->getEntityManager()->commit();
            $userAccount->setProfile($userProfile);

            if (empty($userProfile->getIdcode())) {
                $userProfile->setIdcode(StringHelper::intTo62(100000000 + $userAccount->getId()));
            }
            $this->userProfileRepo->flush($userProfile);
            return CallResultHelper::success($userAccount);
        } catch (UniqueConstraintViolationException $exception) {
            $this->userAccountRepo->getEntityManager()->rollback();
            return CallResultHelper::fail("user had register", $exception->getMessage());
        } catch (\Exception $exception) {
            $this->userAccountRepo->getEntityManager()->rollback();
            return CallResultHelper::fail("register fail", $exception->getTraceAsString());
        }
    }

    /**
     * @param $userAccount
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    function delete($userAccount)
    {
        if (!$userAccount instanceof UserAccount) return CallResultHelper::fail();

        $map = [
            'id' => $userAccount->getId()
        ];

        $user = $this->userAccountRepo->findOneBy($map);
        if ($user instanceof UserAccount && $user->getStatus() != StatusEnum::SOFT_DELETE) {
            $user->setUsername($user->getUsername().'-del'. time());
            $user->setStatus(StatusEnum::SOFT_DELETE);
            $this->userAccountRepo->flush();
        }
        return CallResultHelper::success();
    }

    function findOne($map)
    {
        return $this->userAccountRepo->findOneBy($map);
    }

    /**
     * 更新密码
     * @param $id
     * @param $password
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePwd($id, $password)
    {
        return $this->updatePassword(['id' => $id], $password);
    }

    /**
     * @param array $map
     * @param string $newPassword
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    function updatePassword($map, $newPassword)
    {
        $salt = StringHelper::randAlphabet(8);
        if (!($map instanceof UserAccount)) {
            $userAccount = $this->userAccountRepo->findOneBy($map);
        } else {
            $userAccount = $map;
        }
        $userAccount->setSalt($salt);
        if ($userAccount == null) throw new \Exception("user not register");
        $newPassword = $this->pwdEncoder->encodePassword($userAccount, $newPassword);
        $userAccount->setPassword($newPassword);
        $this->userAccountRepo->flush();
        return $userAccount;
    }

    /**
     * 通过手机号来更新密码
     * @param $projectId
     * @param $mobile
     * @param $password
     * @param $countryNo
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePwdByMobile($projectId, $mobile, $password, $countryNo)
    {
        $map = [
            'project_id' => $projectId,
            'mobile' => $mobile,
            'country_no' => $countryNo
        ];
        return $this->updatePassword($map, $password);
    }

    /**
     * 验证uid对应的密码 与psw 是否对应
     * @param integer $uid 用戶id
     * @param string $psw 明文密碼
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function auth($uid, $psw)
    {
        $user = $this->userAccountRepo->findOneBy(['id' => $uid]);
        if ($user instanceof UserAccount) {
            return $this->pwdEncoder->isPasswordValid($user, $psw);
        }
        return false;
    }

    /**
     * 檢查用戶名是否存在
     * 1. 用戶名
     * 2. 手機號
     * @param $projectId
     * @param string $username 用戶名
     * @param string $countryNo 手機區號
     * @return UserAccount|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkUsername($projectId, $username, $countryNo = '+86')
    {
        $user = $this->userAccountRepo->findOneBy(['project_id' => $projectId, 'username' => $username]);
        if ($user instanceof UserAccount) {
            return $user;
        } else {
            $user = $this->userAccountRepo->findOneBy(['project_id' => $projectId, 'mobile' => $username, 'country_no' => $countryNo]);
            if ($user instanceof UserAccount) {
                return $user;
            }
        }
        return null;
    }
}
