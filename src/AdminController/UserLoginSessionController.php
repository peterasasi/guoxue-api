<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 15:04
 */

namespace App\AdminController;

use App\Dto\UserLoginDto;
use App\Entity\AuthRole;
use App\Entity\Clients;
use App\Entity\LoginSession;
use App\Entity\Menu;
use App\Entity\UserAccount;
use App\Entity\UserProfile;
use App\Helper\ValidatorErrorHelper;
use App\ServiceInterface\ClientsServiceInterface;
use App\ServiceInterface\MenuServiceInterface;
use App\ServiceInterface\SecurityCodeServiceInterface;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\encrypt\constants\TransportEnum;
use by\component\security_code\constants\SecurityCodeType;
use by\component\third_login\Weixin\OAuth2;
use by\component\user\enum\UserLogType;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\ByEnv;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * 用户登录会话
 * @package App\AdminController
 */
class UserLoginSessionController extends BaseNeedLoginController
{

    /**
     * @var UserAccountServiceInterface
     */
    protected $userService;

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
    protected $userProfileService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ClientsServiceInterface
     */
    private $clientsService;

    public function __construct(UserAccountServiceInterface $userAccountService, UserProfileServiceInterface $userProfileService,
                                ClientsServiceInterface $clientsService, ValidatorInterface $validator, MenuServiceInterface $menuService,
                                UserLogServiceInterface $userLogService, SecurityCodeServiceInterface $securityCodeService,
                                UserPasswordEncoderInterface $passwordEncoder, LoginSessionInterface $loginSession,
                                KernelInterface $kernel)
    {
        $this->userProfileService = $userProfileService;
        $this->clientsService = $clientsService;
        $this->validator = $validator;
        $this->userLogService = $userLogService;
        $this->securityCodeService = $securityCodeService;
        $this->userService = $userAccountService;
        $this->passwordEncoder = $passwordEncoder;
        $this->menuService = $menuService;
        parent::__construct($userAccountService, $loginSession, $kernel);
    }


    public function queryInfo(PagingParams $pagingParams, $username = '') {
        $map = [
            'status' => 1
        ];
        if (!empty($mobile)) {
            $map['username'] = ['like', '%'.$username . '%'];
        }

        $ret = $this->userAccountService->queryAndCount($map, $pagingParams, ['id' => 'desc'], ["id", "mobile", "country_no", "create_time", "project_id", "create_time", "last_login_time"]);
        if ($ret instanceof CallResult) {
            if ($ret->isFail()) return $ret;
            $data = $ret->getData();
            foreach ($data['list'] as &$vo) {
                $profile = $this->userProfileService->info(['user' => $vo['id']]);
                if ($profile instanceof UserProfile) {
                    $vo['nickname'] = $profile->getNickname();
                    $vo['head'] = $profile->getHead();
                }
            }

            return CallResultHelper::success($data);
        }
        return $ret;
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
     * @throws \by\component\third_login\ApiException
     */
    public function weixinLogin($authCode, $mobile, $code, $deviceToken , $deviceType,  $countryNo = '86', $password = '') {
//        $api = new OAuth2(ByEnv::get('WX_APP_ID'), ByEnv::get('WX_APP_SECRET'), "");
//        $token = $api->getAccessToken('a', $authCode, 'a');
//        $userInfo = $api->getUserInfo($token);
//        if (array_key_exists('openid', $userInfo) && array_key_exists('unionid', $userInfo)) {
//            $openId = $userInfo['openid'];
//            $unionid = $userInfo['unionid'];
//            $userAccount = $this->userService->info(['openid' => $openId]);
//            if (!($userAccount instanceof UserAccount)) {
//                return $this->registerByMobileCode($mobile, $code, $countryNo, $password, $openId, $unionid);
//            } else {
//                $loginInfo = $this->request->getClientIp();
//                // 2. 登录会话
//                $session = $this->loginSession->login($userAccount->getId(), $deviceToken, $deviceType, $loginInfo, $userAccount->getLoginDeviceCnt(), 7 * 24 * 3600);
//                if ($session instanceof LoginSession) {
//                    $session = $session->getLoginSessionId();
//                }
//                $dto = new UserLoginDto();
//                $dto->setUserAccount($userAccount);
//                $dto->setSid($session);
//                return $dto;
//            }
//        } else {
//            return CallResultHelper::fail('weixin oauth2 failed');
//        }
    }

    public function registerByMobileCode($mobile, $code, $countryNo = '86', $username = '', $password = '', $openId = '', $unionid = '', $idcode = '')
    {

        if (empty(trim($countryNo))) $countryNo = '86';
        // 校验短信验证码
        $callResult = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_REGISTER, $this->getClientId());
        if ($callResult->isFail()) return $callResult;

        $userAccount = new UserAccount();
        $userAccount->setCountryNo($countryNo);
        $userAccount->setMobile($mobile);
        if (empty($username)) {
            $username = 'm' . trim($countryNo, "+") . $mobile;
        } else {
            $uaExists = $this->userAccountService->info(['username' => $username]);
            if ($uaExists instanceof UserAccount) {
                return CallResultHelper::fail('该用户名已注册');
            }
        }
        $userAccount->setUsername($username);
        if (empty($password)) $password = substr(md5($mobile . time()), 0, 16);
        $userAccount->setPassword($password);
        $userAccount->setRegIp(ip2long($this->request->getClientIp()));
        $userAccount->setProjectId($this->getProjectId());
        $userAccount->setLastLoginTime(time());
        $userAccount->setMobileAuth(true);
        if (!empty($password)) {
            $userAccount->setPasswordSet(1);
        }
        $userProfile = new UserProfile();
        $userProfile->setNickname('手机用户' . time());

        $errors = $this->validator->validate($userAccount);

        if (count($errors) > 0) {
            return CallResultHelper::fail(ValidatorErrorHelper::simplify($errors));
        }

        $inviteUser = $this->userProfileService->info(['idcode' => $idcode]);
        if ($inviteUser instanceof UserProfile) {
            $userProfile->setInviteUid($inviteUser->getUid());
        }

        $ret = $this->userService->create($userAccount, $userProfile);

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
     * @param $nickname
     * @return CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function updateInfo($nickname) {
        $this->checkLogin();
        $user = $this->userService->info(['id' => $this->getUid()]);
        if ($user instanceof UserAccount) {
            $nickname = mb_substr($nickname, 0, 32);
            $user->getProfile()->setNickname($nickname);
            $this->userProfileService->flush($user->getProfile());
            return CallResultHelper::success();
        }
        return 'record not exists';
    }

    /**
     * @param $oldPwd
     * @param $newPwd
     * @return CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function updatePassword($oldPwd, $newPwd)
    {
        $this->checkLogin();

        $user = $this->userService->info(['id' => $this->getUid()]);
        if ($user instanceof UserAccount) {
            if (!$this->passwordEncoder->isPasswordValid($user, $oldPwd)) {
                return 'invalid password';
            }
            $newPwd = $this->passwordEncoder->encodePassword($user, $newPwd);
            $user->setPassword($newPwd);
            $this->userService->flush($user);
            return CallResultHelper::success();
        }
        return 'record not exists';
    }

    /**
     * @param $uid
     * @return CallResult|mixed|string
     * @throws \by\component\exception\NotLoginException
     */
    public function adminData($uid)
    {

        $this->checkLogin();

        $data = [
            'platformInfo' => 'Management',
            'userInfo' => [],
            'menuList' => []
        ];
        $userInfo = $this->userService->info(['id' => $uid]);
        if (!($userInfo instanceof UserAccount)) {
            return 'account not exists';
        }
        $client = $this->clientsService->info(['uid' => $uid, 'api_alg' => TransportEnum::Nothing]);
        // 默认的ClientId
        $data['userInfo']['client_id'] = 'by04esfH0fdc6Y';
        if ($client instanceof Clients) {
            $data['userInfo']['client_id'] = $client->getClientId();
        }
        $data['userInfo']['nickname'] = $userInfo->getProfile()->getNickname();
        $data['userInfo']['id'] = $userInfo->getId();
        $data['userInfo']['username'] = $userInfo->getUsername();
        $data['userInfo']['head'] = $userInfo->getProfile()->getHead();
        $data['userInfo']['mobile'] = (new UserLoginDto())->hideMobile($userInfo->getMobile());
        $data['userInfo']['username'] = $data['userInfo']['mobile'];

        $roles = $userInfo->getRoles()->filter(function ($item) {
            return $item instanceof AuthRole && $item->getEnable() == StatusEnum::ENABLE;
        });

        $isRoot = false;
        $menuIds = '';
        foreach ($roles as $role) {
            if ($role instanceof AuthRole) {
                if ($role->getId() === 1) {
                    $isRoot = true;
                    break;
                }
                // 只查询后台菜单
                $menus = $role->getMenus()->filter(function ($item) {
                    return $item instanceof Menu && $item->getScene() == Menu::BackendMenu;
                });
                foreach ($menus as $menu) {
                    if ($menu instanceof Menu) {
                        $menuIds .= $menu->getId() . ',';
                    }
                }
            }
        }

        if (!$isRoot && empty($menuIds)) {
            return 'permission denied';
        }

        $map = ['status' => StatusEnum::ENABLE, 'scene' => Menu::BackendMenu];
        if (!$isRoot) {
            // 如果不是根系统管理员
            $map['id'] = ['in', explode(',', $menuIds)];
        }
        $list = $this->menuService->queryAllBy($map);
        $data['menuList'] = $list;
        return CallResultHelper::success($data);
    }

    /**
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

        $userAccount = $this->userService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
        if (!($userAccount instanceof UserAccount)) {
            return 'User Not Exists';
        }

        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);
        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "Login Success By Mobile and code", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
        }

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
        $isAdminUser = $userAccount->getRoles()->exists(function ($key, $element) {
            if ($element instanceof AuthRole) {
                return $element->getId() == 2;// 特殊2 作为后台登录用户
            }
            return false;
        });

        if (!$isAdminUser) {
            return '该用户不是管理员';
        }

        $loginInfo = $this->request->getClientIp();
        // 2. 登录会话
        $session = $this->loginSession->login($userAccount->getId(), $deviceToken, $deviceType, $loginInfo, $userAccount->getLoginDeviceCnt(), 7 * 24 * 3600);
        if ($session instanceof LoginSession) {
            $session = $session->getLoginSessionId();
        }


        $dto = new UserLoginDto();
        $dto->setUserAccount($userAccount);
        $dto->setSid($session);
        $userAccount->setLastLoginTime(time());
        $userAccount->setLastLoginIp(ip2long($this->request->getClientIp() ?? '127.0.0.1'));
        $this->userService->flush($userAccount);
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
    public function loginByMobilePassword(string $loginInfo, $deviceToken, $deviceType, $mobile, $password, $countryNo = "86", $verifyId = '', $verifyCode = '')
    {
        $countryNo = trim($countryNo, '+');

        // 验证码校验
        if (!empty($verifyId)) {
            $ret = $this->securityCodeService->isLegalById($verifyId, $verifyCode, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId(), false);
            if ($ret->isFail()) return $ret;
        }

        // 账户登录
        $userAccount = $this->userService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
        if (!($userAccount instanceof UserAccount)) {
            return "account not exists";
        }

        if (!$this->passwordEncoder->isPasswordValid($userAccount, $password)) {
            $this->userLogService->log($userAccount->getId(), "用户登录失败(密码错误)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
            return "invalid password";
        }
        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);

        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "用户登录成功(手机号+密码)", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
        }

        return $dto;
    }

    public function autoRegister($mobile, $code, $countryNo = "86", $loginInfo = "", $deviceToken = '', $deviceType = '')
    {
        if (empty(trim($countryNo))) $countryNo = '86';
        $ret = $this->securityCodeService->isLegalCode($code, $this->getProjectId() . '_' . $countryNo . '_' . $mobile, SecurityCodeType::TYPE_FOR_LOGIN, $this->getClientId());
        if ($ret->isFail()) return $ret;

        $userAccount = $this->userService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
        if (!($userAccount instanceof UserAccount)) {
            $dto = $this->registerByMobileCode($mobile, $code, $countryNo);
            if ($dto instanceof UserLoginDto) {
                $userAccount = $this->userService->findOne(['project_id' => $this->getProjectId(), 'mobile' => $mobile, 'country_no' => $countryNo]);
            } else {
                return $dto;
            }
        }

        $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $userAccount);
        if ($dto instanceof UserLoginDto) {
            $this->userLogService->log($dto->getId(), "Login Success By Mobile and code", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
        }
        return $dto;
    }

    /**
     * 登录（通过用户名+密码）
     * @param $username
     * @param $password
     * @param string $loginInfo
     * @param string $deviceToken
     * @param string $deviceType
     * @return \by\infrastructure\base\CallResult
     */
    public function loginByUsername($username, $password, $loginInfo = "", $deviceToken = '', $deviceType = '') {

        $result = $this->userAccountService->findOne(['username' => $username]);
        if ($result instanceof UserAccount) {
            if (!$this->passwordEncoder->isPasswordValid($result, $password)) {
                return CallResultHelper::fail("密码错误");
            }

            $dto = $this->loginUserAccount($loginInfo, $deviceToken, $deviceType, $result);
            if ($dto instanceof UserLoginDto) {
                $this->userLogService->log($dto->getId(), "Login Success By Username and password", UserLogType::LOGIN, $this->request->getClientIp() ?? "", $this->context->getAppType(), $this->request->headers->get('user-agent') ?? "");
            }

            return CallResultHelper::success($dto);
        }
        return CallResultHelper::fail('该用户不存在');
    }
}
