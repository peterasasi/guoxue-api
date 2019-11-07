<?php

namespace App\Controller;

use App\Dto\UserLoginDto;
use App\Entity\LoginSession;
use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Entity\UserGrade;
use App\Entity\UserProfile;
use App\Events\UserRegisterEvent;
use App\Helper\ValidatorErrorHelper;
use App\Message\UserRegisterMsg;
use App\ServiceInterface\ClientsServiceInterface;
use App\ServiceInterface\MenuServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\SecurityCodeServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\exception\NotLoginException;
use by\component\helper\ValidateHelper;
use by\component\paging\vo\PagingParams;
use by\component\third_login\ApiException as ApiExceptionAlias;
use by\infrastructure\constants\BaseErrorCode;
use by\infrastructure\helper\TimeHelper;
use Dbh\SfCoreBundle\Common\ByEnv;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserGradeServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\security_code\constants\SecurityCodeType;
use by\component\third_login\Weixin\OAuth2;
use by\component\user\enum\UserLogType;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * 用户登录会话
 * @package App\Controller
 */
class UserLoginSessionController extends BaseNeedLoginController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var SecurityCodeServiceInterface
     */
    protected $securityCodeService;

    /**
     * @var UserLogServiceInterface
     */
    protected $userLogService;

    /**
     * @var MenuServiceInterface
     */
    protected $menuService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ClientsServiceInterface
     */
    private $clientsService;

    protected $userProfileService;

    protected $gradePaymentChannelService;
    protected $userGradeService;
    protected $walletService;
    protected $logger;
    protected $profitGraphService;
    protected $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ProfitGraphServiceInterface $profitGraphService,
        UserWalletServiceInterface $walletService,
        LoggerInterface $logger,
        UserGradeServiceInterface $userGradeService,
        UserAccountServiceInterface $userAccountService, UserProfileServiceInterface $userProfileService,
        ClientsServiceInterface $clientsService, ValidatorInterface $validator,
        MenuServiceInterface $menuService, UserLogServiceInterface $userLogService,
        SecurityCodeServiceInterface $securityCodeService, UserPasswordEncoderInterface $passwordEncoder,
        LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->profitGraphService = $profitGraphService;
        $this->walletService = $walletService;
        $this->logger = $logger;
        $this->userGradeService = $userGradeService;
        $this->userProfileService = $userProfileService;
        $this->clientsService = $clientsService;
        $this->validator = $validator;
        $this->userLogService = $userLogService;
        $this->securityCodeService = $securityCodeService;
        $this->passwordEncoder = $passwordEncoder;
        $this->menuService = $menuService;
        parent::__construct($userAccountService, $loginSession, $kernel);
    }


    /**
     * 换绑手机号 通过旧手机号+验证码进行更换
     * @param $oldMobileCode
     * @param $newCountryNo
     * @param $newMobile
     * @param $newMobileCode
     * @return CallResult
     * @throws NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeMobile($oldMobileCode, $newCountryNo, $newMobile, $newMobileCode) {
        $this->checkLogin();
        $user = $this->getUser();
        if ($user instanceof UserAccount) {
            $oldMobile = $user->getMobile();
            $oldCountryNo = $user->getCountryNo();

            $existAccount = $this->userAccountService->findOne(['mobile' => $newMobile, 'country_no' => $newCountryNo]);
            if ($existAccount instanceof UserAccount) {
                return CallResultHelper::fail($newMobile.'手机号已存在');
            }
            $ret = $this->securityCodeService->isLegalCode($oldMobileCode, $this->getProjectId() . '_' . $oldCountryNo . '_' . $oldMobile, SecurityCodeType::TYPE_FOR_CHANGE_NEW_PHONE, $this->getClientId());
            if ($ret->isFail()) return $ret;

            $ret = $this->securityCodeService->isLegalCode($newMobileCode, $this->getProjectId() . '_' . $newCountryNo . '_' . $newMobile, SecurityCodeType::TYPE_FOR_CHANGE_NEW_PHONE, $this->getClientId());
            if ($ret->isFail()) return $ret;

            $user->setMobile($newMobile);
            $user->setCountryNo($newCountryNo);
            $this->userAccountService->flush($user);
            return CallResultHelper::success();
        }
        return CallResultHelper::fail();
    }

    /**
     * @param $authCode
     * @param $mobile
     * @param $code
     * @param $deviceToken
     * @param $deviceType
     * @param string $countryNo
     * @param string $password
     * @return UserLoginDto|CallResult
     * @throws ApiExceptionAlias
     */
    public function weixinLogin($authCode, $mobile, $code, $deviceToken, $deviceType, $countryNo = '86', $password = '')
    {
        $api = new OAuth2(ByEnv::get('WX_APP_ID'), ByEnv::get('WX_APP_SECRET'), "");
        $token = $api->getAccessToken('a', $authCode, 'a');
        $userInfo = $api->getUserInfo($token);
        if (array_key_exists('openid', $userInfo) && array_key_exists('unionid', $userInfo)) {
            $openId = $userInfo['openid'];
            $unionid = $userInfo['unionid'];
            $userAccount = $this->userAccountService->info(['openid' => $openId]);
            if (!($userAccount instanceof UserAccount)) {
                $dto = $this->registerByMobileCode($mobile, $code, $countryNo, $password, $openId, $unionid);
                if (!($dto instanceof UserLoginDto)) {
                    return $dto;
                }
                $userAccount = $this->userAccountService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
            }

            if (!($userAccount instanceof UserAccount)) {
                return CallResultHelper::fail('login failed');
            }

            $loginInfo = $this->request->getClientIp();
            $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);
            if ($dto instanceof UserLoginDto) {
                $this->userLogService->log($dto->getId(), "Login Success By Weixin", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
            }
            return $dto;
        } else {
            return CallResultHelper::fail('weixin oauth2 failed');
        }
    }

    /**
     * 更新用户信息 昵称、头像 都可选
     * @param string $nickname
     * @param string $head
     * @return CallResult|string
     * @throws NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateInfo($nickname = '', $head = '')
    {
        $this->checkLogin();
        $nickname = trim($nickname);
        $user = $this->userProfileService->info(['user' => $this->getUid()]);
        if ($user instanceof UserProfile) {

            if (!empty($nickname)) {
                $nickname = mb_substr($nickname, 0, 32);
                $user->setNickname($nickname);
            }

            if (!empty($head)) {
                $user->setHead($head);
            }

            $this->userProfileService->flush($user);
            return CallResultHelper::success();
        }
        return 'record not exists';
    }


    /**
     * @param $oldPwd
     * @param $newPwd
     * @return CallResult|string
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePwdByOldPwd($oldPwd, $newPwd)
    {
        $this->checkLogin();

        $user = $this->userAccountService->info(['id' => $this->getUid()]);
        if ($user instanceof UserAccount) {
            if (!$this->passwordEncoder->isPasswordValid($user, $oldPwd)) {
                return 'invalid password';
            }
            $newPwd = $this->passwordEncoder->encodePassword($user, $newPwd);
            $user->setPassword($newPwd);
            $this->userAccountService->flush($user);
            return CallResultHelper::success();
        }
        return 'record not exists';
    }

    /**
     * 通过手机号+验证码登录
     * @param $deviceToken
     * @param $deviceType
     * @param $mobile
     * @param $code
     * @param string $countryNo
     * @param string $loginInfo
     * @return UserLoginDto|CallResult|string
     */
    public function loginByMobileCode($deviceToken, $deviceType, $mobile, $code, $countryNo = "86", $loginInfo = "")
    {
        $ret = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId());
        if ($ret->isFail()) return $ret;

        $userAccount = $this->userAccountService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
        if (!($userAccount instanceof UserAccount)) {
            return 'User Not Exists';
        }

        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);
        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "Login Success By Mobile and code", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
        }

        return $dto;
    }

    /**
     * @param UserAccount $userAccount
     * @return UserLoginDto|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function checkLoginUserAccount(UserAccount $userAccount)
    {
        if ($userAccount->getStatus() == StatusEnum::SOFT_DELETE) {
            return "account had deleted";
        }
        if ($userAccount->getStatus() == StatusEnum::DISABLED) {
            return "account had disabled";
        }

        $userGrade = $this->userGradeService->info(['uid' => $userAccount->getId()]);
        if (!($userGrade instanceof UserGrade)) {
            $userGrade = new UserGrade();
            $userGrade->setUid($userAccount->getId());
            $userGrade->setGradeId(UserGrade::Normal);
            $userGrade->setStatus(StatusEnum::ENABLE);
            $this->userGradeService->add($userGrade);
        }

        $dto = new UserLoginDto();
        $dto->setUserAccount($userAccount);
        $dto->setSid($this->getSId());
        $profitGraph = $this->profitGraphService->init($userAccount->getUsername(), $userAccount->getId(), $userAccount->getMobile(), $userAccount->getProfile()->getInviteUid());
        if ($profitGraph instanceof ProfitGraph) {
            $dto->setVipLevel($profitGraph->getVipLevel());
        }
//        $dto->setGradeId($userGrade->getGradeId());

        $userAccount->setLastLoginTime(time());
        $userAccount->setLastLoginIp(ip2long($this->request->getClientIp() ?? '127.0.0.1'));
        $this->userAccountService->flush($userAccount);
        return $dto;
    }


    protected function loginUserAccount($loginInfo, $deviceToken, $deviceType, UserAccount $userAccount)
    {
        if ($userAccount->getStatus() == StatusEnum::SOFT_DELETE) {
            return "account had deleted";
        }
        if ($userAccount->getStatus() == StatusEnum::DISABLED) {
            return "account had disabled";
        }
        $loginInfo = $this->request->getClientIp();

        // 2. 登录会话
        $session = $this->loginSession->login($userAccount->getId(), $deviceToken, $deviceType, $loginInfo, $userAccount->getLoginDeviceCnt(), 7 * 24 * 3600);
        if ($session instanceof LoginSession) {
            $session = $session->getLoginSessionId();
        } else {
            // 登录失败
            return 'login session failed';
        }
        $userGrade = $this->userGradeService->info(['uid' => $userAccount->getId()]);
        if (!($userGrade instanceof UserGrade)) {
            $userGrade = new UserGrade();
            $userGrade->setUid($userAccount->getId());
            $userGrade->setGradeId(UserGrade::Normal);
            $userGrade->setStatus(StatusEnum::ENABLE);
            $this->userGradeService->add($userGrade);
        }

        $profitGraph = $this->profitGraphService->init($userAccount->getUsername(), $userAccount->getId(), $userAccount->getMobile(), $userAccount->getProfile()->getInviteUid());

        $dto = new UserLoginDto();
        $dto->setUserAccount($userAccount);
        $dto->setSid($session);
//        $dto->setGradeId($userGrade->getGradeId());
        if ($profitGraph instanceof ProfitGraph) {
            $dto->setVipLevel($profitGraph->getVipLevel());
        }

        $userAccount->setLastLoginTime(time());
        $userAccount->setLastLoginIp(ip2long($this->request->getClientIp() ?? '127.0.0.1'));
        $this->userAccountService->flush($userAccount);
        return $dto;
    }

    /**
     * 通过邮件码更新密码
     * @param $email
     * @param $code
     * @param $newPwd
     * @return CallResult|string
     */
    public function updatePwdByEmailCode($email, $code, $newPwd)
    {

        $callResult = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $email, SecurityCodeType::TYPE_FOR_FOUND_PSW, $this->getClientId());
        if ($callResult->isFail()) return $callResult;

        $result = $this->userAccountService->findOne(['email' => $email, 'project_id' => $this->getProjectId()]);
        if ($result instanceof UserAccount) {
            // 新密码有效性检测
            $ua = (new UserAccount());
            $ua->setPassword($newPwd);
            $errors = $this->validator->validateProperty($ua, "password");
            if (count($errors) > 0) {
                return 'new password -' . ValidatorErrorHelper::simplify($errors);
            }
            $this->userAccountService->updatePassword($result, $newPwd);
            return CallResultHelper::success();
        }

        return 'user not register';
    }

    /**
     * 通过短信更新密码
     * @param $username
     * @param $mobile
     * @param $countryNo
     * @param $code
     * @param $newPwd
     * @return CallResult|string
     */
    public function updatePwdByMobileCode($username, $mobile, $countryNo, $code, $newPwd)
    {
        $callResult = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_FOUND_PSW, $this->getClientId());
        if ($callResult->isFail()) return $callResult;

        $result = $this->userAccountService->findOne(['username' => $username, 'mobile' => $mobile, 'country_no' => $countryNo, 'project_id' => $this->getProjectId()]);
        if ($result instanceof UserAccount) {
            // 新密码有效性检测
            $ua = (new UserAccount());
            $ua->setPassword($newPwd);
            $errors = $this->validator->validateProperty($ua, "password");
            if (count($errors) > 0) {
                return 'new password -' . ValidatorErrorHelper::simplify($errors);
            }
            $this->userAccountService->updatePassword($result, $newPwd);
            return CallResultHelper::success();
        }

        return 'user not register';
    }

    /**
     * 根据uid+sid 获取用户信息
     * @return UserLoginDto|string
     * @throws NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function refresh()
    {
        $this->checkLogin();

        $userAccount = $this->userAccountService->findOne(['id' => $this->getUid()]);
        if (!($userAccount instanceof UserAccount)) {
            throw new NotLoginException();
        }

        $dto = $this->checkLoginUserAccount($userAccount);

        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "用户登录刷新成功(会话id+用户id)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
        } else {
            if (is_string($dto)) {
                throw new NotLoginException($dto);
            } else {
                throw new NotLoginException();
            }
        }

        return $dto;
    }

    /**
     * 账户登录 并 保存会话到数据库
     * @param $verifyId
     * @param $verifyCode
     * @param string $loginInfo
     * @param $deviceToken
     * @param $deviceType
     * @param $mobile
     * @param $password
     * @param string $countryNo
     * @return array|string
     */
    public function loginByMobilePassword($deviceType, $deviceToken, $mobile, $password, $countryNo = "86", string $loginInfo = '', $verifyId = '', $verifyCode = '')
    {
        $countryNo = trim($countryNo, '+');
        // 验证码校验
        if (!empty($verifyId)) {
            $ret = $this->securityCodeService->isLegalById($verifyId, $verifyCode, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId(), false);
            if ($ret->isFail()) return $ret;
        }

        // 账户登录
        $userAccount = $this->userAccountService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
        if (!($userAccount instanceof UserAccount)) {
            return "account not exists";
        }

        if (!$this->passwordEncoder->isPasswordValid($userAccount, $password)) {
            $this->userLogService->log($userAccount->getId(), "用户登录失败(密码错误)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
            return "invalid password";
        }
        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);

        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "用户登录成功(手机号+密码)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
        }

        return $dto;
    }

    public function registerByMobileCode($mobile, $code, $countryNo = '86', $password = '', $openId = '', $unionid = '', $idcode = '')
    {

        if (empty(trim($countryNo))) $countryNo = '86';
        // 校验短信验证码
        $callResult = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_REGISTER, $this->getClientId());
        if ($callResult->isFail()) return $callResult;
        $userAccount = new UserAccount();
        $userAccount->setCountryNo($countryNo);
        $userAccount->setMobile($mobile);
        $username = 'm' . trim($countryNo, "+") . $mobile;
        $userAccount->setUsername($username);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        if (empty($password)) $password = substr(md5($mobile . time()), 0, 16);
        $userAccount->setPassword($password);
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userAccount->setMobileAuth(true);
        $userAccount->setEmail('');

        $userProfile = new UserProfile();
        $userProfile->setNickname('手机用户' . time());
        $userProfile->setInviteUid(0);
        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }
        if (!empty($idcode)) {
            $inviteUser = $this->userProfileService->info(['idcode' => $idcode]);
            if ($inviteUser instanceof UserProfile) {
                $userProfile->setInviteUid($inviteUser->getUid());
            }
        }

        $ret = $this->userAccountService->create($userAccount, $userProfile);

        if ($ret instanceof CallResult) {
            if ($ret->isSuccess()) {
                $profitGraph = $this->profitGraphService->init($userAccount->getUsername(), $userAccount->getId(), $userAccount->getMobile(), $userProfile->getInviteUid());

                $ua = $ret->getData();
                $dto = new UserLoginDto();
                $dto->setUserAccount($ua);
                $dto->setSid("");
                if ($profitGraph instanceof ProfitGraph) {
                    $dto->setVipLevel($profitGraph->getVipLevel());
                }
                $this->registerSuccess($userProfile->getInviteUid(), $ua->getId(), '', '', $mobile, $countryNo);
                return $dto;
            }
        }
        return $ret;
    }


    public function loginByEmail($deviceType, $deviceToken, $email, $password, string $loginInfo = '')
    {

        // 账户登录
        $userAccount = $this->userAccountService->findOne(['project_id' => $this->getProjectId(), 'status' => StatusEnum::ENABLE, 'email' => $email]);
        if (!($userAccount instanceof UserAccount)) {
            return "account not exists";
        }

        if (!$userAccount->isEmailAuth()) {
            // 重新发送一封激活邮件
            $msg = new UserRegisterMsg();
            $msg->setProjectId($userAccount->getProjectId());
            $msg->setUid($userAccount->getId());
            $msg->setEmail($userAccount->getEmail());
            $this->dispatchMessage($msg);
            return CallResultHelper::fail('email is not verified', '', BaseErrorCode::User_Not_Verify_Email);
        }

        if (!$this->passwordEncoder->isPasswordValid($userAccount, $password)) {
            $this->userLogService->log($userAccount->getId(), "用户登录失败(密码错误，邮箱+密码)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
            return "invalid password";
        }
        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);

        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "用户登录成功(邮箱+密码)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
        }

        return $dto;
    }

    protected function getUsernameFromEmail($email)
    {
        $username = str_replace('@', '_', $email);
        $username = str_replace('.', '_', $username);
        $username .= rand(0, 1000);
        $userAccount = $this->userAccountService->info(['username' => $username, 'project_id' => $this->getProjectId()]);

        if ($userAccount instanceof UserAccount) {
            $username = $username . $userAccount->getId();
        }
        return 'm'.$username;
    }

    public function registerByEmail($email, $password)
    {
        if (!ValidateHelper::isEmail($email)) {
            return CallResultHelper::fail('邮箱格式不正确');
        }

        // 不等于删除的情况下
        $info = $this->userAccountService->info(['email' => $email, 'project_id' => $this->getProjectId(), 'status' => ['neq', StatusEnum::SOFT_DELETE]]);

        if (is_array($info)) {
            if ($info['mobileAuth']) {
                return CallResultHelper::fail('该邮箱已注册');
            } else {
                $autoDeleteTime = 3 * 24 * 3600 - (BY_APP_START_TIMESTAMP - $info['createTime']);

                if ($autoDeleteTime < 0) {
                    // 则标记该
                    $this->userAccountService->updateOne(['id' => $info['id']], ['email' => 'DEL' . date("YmdH") . $info['email'], 'username' => 'DEL' . date("YmdH") . $info['username'], 'status' => StatusEnum::SOFT_DELETE]);
                } else {
                    $str = TimeHelper::formatString($autoDeleteTime);
                    return CallResultHelper::fail('该邮箱已注册, 但尚未认证，如果不是你操作，请' . $str . '后再试');
                }
            }
        }

        $username = $this->getUsernameFromEmail($email);

        $userAccount = new UserAccount();
        $userAccount->setEmailAuth(false);
        $userAccount->setEmail($email);
        $userAccount->setUsername($username);
        $userAccount->setPassword($password);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        $userAccount->setMobileAuth(false);
        $userAccount->setMobile('_' . time() . rand(0, 1000));
        $userAccount->setCountryNo('86');
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userAccount->setStatus(StatusEnum::ENABLE);
        $userProfile = new UserProfile();
        $userProfile->setNickname('邮箱用户' . time());

        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }

        $ret = $this->userAccountService->create($userAccount, $userProfile);
        if ($ret instanceof CallResult) {
            if ($ret->isSuccess()) {
                $regUa = $ret->getData();
                if ($regUa instanceof UserAccount) {
                    $this->registerSuccess($userProfile->getInviteUid(), $regUa->getId(), $email, '', '', '');

                    return CallResultHelper::success($regUa->getId());
                } else {
                    return CallResultHelper::fail('register failed');
                }
            }
        }
        return $ret;
    }

    public function registerByUsername($username, $password)
    {
        $userAccount = new UserAccount();
        $userAccount->setUsername($username);
        $userAccount->setPassword($password);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        $userAccount->setMobile('_' . time() . rand(0, 1000));
        $userAccount->setCountryNo('86');
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userProfile = new UserProfile();
        $userProfile->setNickname('普通用户' . time());

        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }

        $ret = $this->userAccountService->create($userAccount, $userProfile);
        if ($ret instanceof CallResult) {
            if ($ret->isSuccess()) {
                $regUa = $ret->getData();
                if ($regUa instanceof UserAccount) {
                    $this->registerSuccess($userProfile->getInviteUid(), $regUa->getId(), '', '', '', '');
                    return CallResultHelper::success($regUa->getId());
                } else {
                    return CallResultHelper::fail('register failed');
                }
            }
        }
        return $ret;
    }


    public function loginByUsername($deviceType, $deviceToken, $username, $password, string $loginInfo = '', $countryNo = "86", $verifyId = '', $verifyCode = '')
    {
        $countryNo = trim($countryNo, '+');
        // 验证码校验
        if (!empty($verifyId)) {
            $ret = $this->securityCodeService->isLegalById($verifyId, $verifyCode, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId(), false);
            if ($ret->isFail()) return $ret;
        }

        // 账户登录
        $userAccount = $this->userAccountService->findOne(['project_id' => $this->getProjectId(), 'username' => $username]);
        if (!($userAccount instanceof UserAccount)) {
            return "account not exists";
        }

        if (!$this->passwordEncoder->isPasswordValid($userAccount, $password)) {
            $this->userLogService->log($userAccount->getId(), "用户登录失败(密码错误)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
            return "invalid password";
        }
        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);

        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "用户登录成功(用户名+密码)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $deviceType, $this->request->headers->get('user-agent') ?? "");
        }

        return $dto;
    }


    /**
     * 查询登录日志
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws NotLoginException
     */
    public function queryLoginHistory(PagingParams $pagingParams)
    {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid(),
            'log_type' => UserLogType::LOGIN
        ];
        return $this->userLogService->queryBy($map, $pagingParams, ["createTime" => "desc"]);
    }

    protected function registerSuccess($inviteUid, $uid, $email, $nickname, $mobile, $countryNo)
    {
        try {
            $this->walletService->safeGetWalletInfo($uid);
        } catch (\Exception $exception) {
            $this->logger->error('创建电子钱包异常-'.$exception->getMessage());
        }

        $event = new UserRegisterEvent();
        $event->setProjectId($this->getProjectId());
        $event->setEmail($email);
        $event->setUid($uid);
        $event->setNickname($nickname);
        $event->setMobile($mobile);
        $event->setInviteUid($inviteUid);

        $this->eventDispatcher->dispatch($event);

//        $msg = new UserRegisterMsg();
//        $msg->setProjectId($this->getProjectId());
//        $msg->setEmail($email);
//        $msg->setUid($uid);
//        $msg->setNickname($nickname);
//        $msg->setMobile($mobile);
//        $this->dispatchMessage($msg);

    }

    /**
     * @param $cnt
     * @return CallResult
     * @throws NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLoginDeviceCnt($cnt)
    {
        $this->checkLogin();
        if ($cnt > 10) $cnt = 10;
        if ($cnt < 0) $cnt = 0;
        $user = $this->userAccountService->info(['id' => $this->getUid()]);
        if ($user instanceof UserAccount) {
            if ($user->getLoginDeviceCnt() != $cnt) {
                $user->setLoginDeviceCnt($cnt);
                $this->userAccountService->flush($user);
            }
        }
        return CallResultHelper::success();
    }

}
