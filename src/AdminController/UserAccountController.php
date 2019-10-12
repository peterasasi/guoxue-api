<?php
/**
// * Created by PhpStorm.
// * User: asasi
// * Date: 2018/8/4
// * Time: 15:20
// */
//
namespace App\AdminController;


use App\Dto\UserLoginDto;
use App\Entity\UserAccount;
use App\Entity\UserProfile;
use App\Helper\ValidatorErrorHelper;
use App\ServiceInterface\SecurityCodeServiceInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\security_code\constants\SecurityCodeType;
use by\component\user\enum\UserLogType;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class UserAccountController extends BaseSymfonyApiController
{

    protected $userProfileService;
    /**
     * @var UserAccountServiceInterface
     */
    private $userAccountService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var SecurityCodeServiceInterface
     */
    private $securityCodeService;
    /**
     * @var UserLogServiceInterface
     */
    private $userLogService;

    public function __construct(UserProfileServiceInterface $userProfileService, UserLogServiceInterface $userLogService, KernelInterface $kernel, SecurityCodeServiceInterface $securityCodeService, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, UserAccountServiceInterface $userAccountService)
    {
        $this->validator = $validator;
        $this->userAccountService = $userAccountService;
        $this->passwordEncoder = $passwordEncoder;
        $this->securityCodeService = $securityCodeService;
        $this->userLogService = $userLogService;
        $this->userProfileService = $userProfileService;
        parent::__construct($kernel);
    }


    /**
     * 查询
     * @param $mobile
     * @param PagingParams $pagingParams
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $mobile = '') {
        $map = [
            'status' => 1
        ];
        if (!empty($mobile)) {
            $map['mobile'] = ['like', $mobile . '%'];
        }

        return $this->userAccountService->queryAndCount($map, $pagingParams, ['id' => 'desc'], ["id", "mobile", "country_no", "create_time", "project_id", "create_time", "last_login_time"]);
    }

    public function queryInfo(PagingParams $pagingParams, $mobile = '') {
        $map = [
            'status' => 1
        ];
        if (!empty($mobile)) {
            $map['mobile'] = ['like', $mobile . '%'];
        }

        $ret = $this->userAccountService->queryAndCount($map, $pagingParams, ['id' => 'desc'], ["id", "mobile", "country_no", "create_time", "project_id", "create_time", "last_login_time"]);
        if ($ret instanceof CallResult) {
            if ($ret->isFail()) return $ret;
            $data = $ret->getData();
            foreach ($data['list'] as &$vo) {
                $profile = $this->userProfileService->info(['user' => $vo['id']]);
                if ($profile instanceof UserProfile) {
                    $vo['_nickname'] = $profile->getNickname();
                    $vo['_head'] = $profile->getHead();
                }
            }

            return CallResultHelper::success($data);
        }
        return $ret;
    }


    /**
     * 根据旧密码来修改新密码
     * @param $username
     * @param $oldPwd
     * @param $newPwd
     * @return string
     */
    public function updatePwdByOldPwd($username, $oldPwd, $newPwd) {

        $result = $this->userAccountService->findOne(['username' => $username]);
        if ($result instanceof UserAccount) {
            if (!$this->passwordEncoder->isPasswordValid($result, $oldPwd)) {
                return "password error";
            }
            // 新密码有效性检测
            $ua = (new UserAccount());
            $ua->setPassword($newPwd);
            $errors = $this->validator->validateProperty($ua, "password");
            if (count($errors) > 0) {
                return 'new password -'.ValidatorErrorHelper::simplify($errors);
            }

            $this->userAccountService->updatePassword($result, $newPwd);
            return "success";
        }

        return 'user not register';
    }

    /**
     * 更新手机号+验证码 + 新密码
     * @param $mobile
     * @param $countryNo
     * @param $code
     * @param $newPwd
     * @return CallResult|string
     */
    public function updatePwdByMobileCode($mobile, $countryNo, $code, $newPwd) {
        $callResult = $this->securityCodeService->isLegalCode($code, $countryNo.'_'.$mobile, SecurityCodeType::TYPE_FOR_UPDATE_PSW, $this->getClientId());
        if ($callResult->isFail()) return $callResult;

        $result = $this->userAccountService->findOne(['mobile' => $mobile, 'country_no' => $countryNo]);
        if ($result instanceof UserAccount) {
            // 新密码有效性检测
            $ua = (new UserAccount());
            $ua->setPassword($newPwd);
            $errors = $this->validator->validateProperty($ua, "password");
            if (count($errors) > 0) {
                return 'new password -'.ValidatorErrorHelper::simplify($errors);
            }
            $this->userAccountService->updatePassword($result, $newPwd);
            return "success";
        }

        return 'user not register';
    }

    /**
     * 登录（通过用户名+密码）
     * @param $username
     * @param $password
     * @return \by\infrastructure\base\CallResult
     */
    public function loginByUsername($username, $password) {
        return CallResultHelper::fail('接口已弃用');
//        $result = $this->userAccountService->findOne(['username' => $username]);
//        if ($result instanceof UserAccount) {
//            if (!$this->passwordEncoder->isPasswordValid($result, $password)) {
//                return CallResultHelper::fail("密码错误");
//            }
//
//            return CallResultHelper::success($result);
//        }
//        return CallResultHelper::fail('该用户不存在');
    }

    /**
     * 根据国家区号 + 手机号+密码进行登录
     * @param string $mobile
     * @param string $password
     * @param string $countryNo
     * @return string
     */
    public function loginByMobilePassword($mobile, $password, $countryNo) {
        return CallResultHelper::fail('请使用服务 By_UserLoginSession_loginByMobilePassword');
//        $userAccount = $this->userAccountService->findOne(['project_id'=> $this->getProjectId(), 'mobile'=>$mobile, 'country_no' => $countryNo]);
//        if (!($userAccount instanceof UserAccount)) {
//            return "account not exists";
//        }
//        if (!$this->passwordEncoder->isPasswordValid($userAccount, $password)) {
//            return "invalid password";
//        }
//        if ($userAccount->getStatus() == StatusEnum::SOFT_DELETE) {
//            return "account had deleted";
//        }
//        if ($userAccount->getStatus() == StatusEnum::DISABLED) {
//            return "account had disabled";
//        }
//        return $userAccount;
    }

    /**
     * 根据国家区号 + 手机号 + 手机短信验证码登录
     * @param $mobile
     * @param $code
     * @param string $countryNo
     * @return \by\infrastructure\base\CallResult
     */
    public function loginByMobileCode($mobile, $code, $countryNo = '86') {
        return CallResultHelper::fail('请使用服务 By_UserLoginSession_loginByMobileCode');
        // 校验短信验证码
//        $callResult = $this->securityCodeService->isLegalCode($code, $countryNo.'_'.$mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId());
//        if ($callResult->isFail()) return $callResult;
//        return $this->userAccountService->findOne(['project_id'=> $this->getProjectId(), 'mobile'=>$mobile, 'country_no' => $countryNo]);
    }

    /**
     * 后台手动创建用户
     * @param $mobile
     * @param string $countryNo
     * @param string $password
     * @param string $idcode
     * @return UserLoginDto|CallResult
     */
    public function registerByMobile($mobile, $countryNo = '86',  $password = '', $idcode = '') {

        $userAccount = new UserAccount();
        $userAccount->setCountryNo($countryNo);
        $userAccount->setMobile($mobile);
        $username = 'm'.trim($countryNo, "+").$mobile;
        $userAccount->setUsername($username);
        if (empty($password)) $password = substr(md5($mobile.time()),0, 16);
        $userAccount->setPassword($password);
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userAccount->setMobileAuth(true);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        $userProfile = new UserProfile();
        $userProfile->setNickname('手机用户'.time());

        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }

        $inviteUser = $this->userProfileService->info(['idcode' => $idcode]);
        if ($inviteUser instanceof  UserProfile) {
            $userProfile->setInviteUid($inviteUser->getUid());
        }

        $ret = $this->userAccountService->create($userAccount, $userProfile);

        if ($ret instanceof CallResult) {
            if ($ret->isSuccess()) {
                $ua = $ret->getData();
                if ($ua instanceof UserAccount) {
                    $note = 'Create New User ' . $ua->getId();
                    if ($this->getUid()) {
                        $this->userLogService->log($this->getUid(), $note, UserLogType::Operation, $this->request->getClientIp(), $this->getAppType() ?? "", $this->request->headers->get('user-agent') ?? "");
                    }
                    return CallResultHelper::success($ua->getId());
                }
            }
        }
        return $ret;
    }

    /**
     * 通过用户名注册
     * @param $username
     * @param $password
     * @return UserLoginDto|CallResult
     */
    public function registerByUsername($username, $password) {
        $userAccount = new UserAccount();
        $userAccount->setUsername($username);
        $userAccount->setPassword($password);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        $userAccount->setMobile('_'.time().rand(0, 1000));
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userProfile = new UserProfile();
        $userProfile->setNickname('普通用户'.time());

        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }

        $ret = $this->userAccountService->create($userAccount, $userProfile);
        if ($ret instanceof CallResult) {
            if ($ret->isSuccess()) {
                $dto = new UserLoginDto();
                $dto->setUserAccount($ret->getData());
                $dto->setSid("");
                return $dto;
            }
        }
        return $ret;
    }

    /**
     * 用户删除 - 假删除
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $userAccount = new UserAccount();
        $userAccount->setId($id);
        $ret = $this->userAccountService->delete($userAccount);
        return CallResultHelper::success($ret);
    }

    /**
     * 单个用户信息
     * @param $username
     * @return mixed
     */
    public function info($username) {
        return $this->userAccountService->findOne(['username' => $username]);
    }
}
