<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 15:04
 */

namespace App\Controller;


use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

/**
 * 登录会话 - 数据库
 * Class LoginSessionController
 * @package App\Controller
 */
class LoginSessionController extends BaseNeedLoginController
{

    /**
     * LoginSessionController constructor.
     * @param UserAccountServiceInterface $userAccountService
     * @param LoginSessionInterface $loginSession
     * @param KernelInterface $kernel
     */
    public function __construct(UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
    }

    /**
     * 查询某个用户会话
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query(PagingParams $pagingParams) {
        $this->checkLogin();
        return $this->loginSession->query($this->getUid(), $pagingParams, ['expireTime' => 'asc']);
    }

    /**
     * 登出会话
     * @return mixed
     */
    public function logout() {
        return $this->loginSession->logout($this->getUid(), $this->getSId());
    }
}
