<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 15:04
 */

namespace App\AdminController;

use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

/**
 * 登录会话 - 数据库
 * Class LoginSessionController
 * @package App\AdminController
 */
class LoginSessionController extends BaseSymfonyApiController
{
    /**
     * @var LoginSessionInterface
     */
    protected $service;

    public function __construct(LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        $this->service = $loginSession;
        parent::__construct($kernel);
    }

    /**
     * 查询某个用户会话
     * @param $uid
     * @param PagingParams $pagingParams
     * @return mixed
     */
    public function query($uid, PagingParams $pagingParams) {
        return $this->service->query($uid, $pagingParams, ['uid' => 'asc']);
    }

    /**
     * 检测会话是否有效
     * @param $uid
     * @param $loginSessionId
     * @param $deviceType
     * @param int $sessionExpireTime
     * @return CallResult
     */
    public function check($uid, $loginSessionId, $deviceType, $sessionExpireTime = 1296000)
    {
        return $this->service->check($uid, $loginSessionId, $deviceType, $sessionExpireTime);
    }

    /**
     * 登录
     * @param $uid
     * @param string $deviceToken 登录设备唯一标志
     * @param string $deviceType 登录设备类型
     * @param string $loginInfo 登录额外信息
     * @param int $loginSessionMaxCount
     * @param int $sessionExpireTime
     * @return mixed
     */
    public function login($uid, $deviceToken, $deviceType, $loginInfo, $loginSessionMaxCount = 1, $sessionExpireTime = 1296000) {
        return $this->service->login($uid, $deviceToken, $deviceType, $loginInfo, $loginSessionMaxCount, $sessionExpireTime);
    }

    /**
     * 登出会话
     * @param $uid
     * @param $s_id
     * @return mixed
     */
    public function logout($uid, $s_id) {
        return $this->service->logout($uid, $s_id);
    }
}
