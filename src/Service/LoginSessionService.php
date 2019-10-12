<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 14:11
 */

namespace App\Service;


use App\Entity\LoginSession;
use App\Repository\LoginSessionRepository;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;


class LoginSessionService extends BaseService implements LoginSessionInterface
{
    /**
     * @var LoginSessionRepository
     */
    protected $repo;

    public function __construct(LoginSessionRepository $repository)
    {
        $this->repo = $repository;
    }

    function query($uid, PagingParams $pagingParams, $order = [], $fields = [])
    {
        return $this->repo->queryBy(['uid' => $uid, 'expire_time' => ['gt', 0]], $pagingParams, ["id" => 'asc']);
    }


    /**
     * 校验是否有效 并更新
     * @param $uid
     * @param $loginSessionId
     * @param $deviceType
     * @param int $sessionExpireTime
     * @return \by\infrastructure\base\CallResult|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function check($uid, $loginSessionId, $deviceType, $sessionExpireTime = 1296000)
    {
        // TODO: 考虑加上deviceType作为验证
        $loginSessionEntity = $this->repo->findOneBy(['uid' => $uid, 'login_session_id' => $loginSessionId]);
        if (!($loginSessionEntity instanceof LoginSession)) {
            return CallResultHelper::fail('your session had expired');
        }
        $now = time();
        // 过期是否
        if ($now > $loginSessionEntity->getExpireTime()) {
            return CallResultHelper::fail('your session had expired');
        }
        // 更新过期时间
        $loginSessionEntity->setExpireTime($now + $sessionExpireTime);
        $this->repo->flush($loginSessionEntity);
        return CallResultHelper::success();
    }

    /**
     * @param $uid
     * @param $deviceToken
     * @param $deviceType
     * @param $loginInfo
     * @param int $loginSessionMaxCount
     * @param int $sessionExpireTime
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function login($uid, $deviceToken, $deviceType, $loginInfo, $loginSessionMaxCount = 1, $sessionExpireTime = 1296000)
    {

        $map = ['uid' => $uid];
        $cnt = $this->repo->count($map);
        $now = time();
        $logSessionId = StringHelper::intTo36Hex($uid) . '#' . md5($uid . $deviceToken . time());
        if (empty($sessionExpireTime) || $sessionExpireTime <= 10) {
            $sessionExpireTime = 300;
        }
        $sessionExpireTime = intval($sessionExpireTime);
        if ($cnt >= $loginSessionMaxCount) {

            // 先查找 deviceType 或 deviceToken 相同的 踢掉
            //
            $preLoginSession = $this->getPreLoginSession($uid, $deviceType);

            if ($preLoginSession instanceof LoginSession) {
                // 找到之前登录过的记录, 更新记录
                $now = time();
                $preLoginSession->setDeviceToken($deviceToken);
                $preLoginSession->setLoginSessionId($logSessionId);
                $preLoginSession->setCreateTime($now);
                $preLoginSession->setLoginInfo(json_encode($loginInfo));
                $preLoginSession->setExpireTime($now + $sessionExpireTime);
                $preLoginSession->setLoginDeviceType($deviceType);
                $this->repo->flush();
                return $preLoginSession;
            } else {
                return CallResultHelper::fail();
            }
        }
        //至少5分钟
        $entity = new LoginSession();
        $entity->setDeviceToken($deviceToken);
        $entity->setUid($uid);
        $entity->setLoginSessionId($logSessionId);
        $entity->setExpireTime($now + $sessionExpireTime);
        $entity->setLoginDeviceType($deviceType);
        $entity->setLoginInfo(json_encode($loginInfo));
        $this->repo->add($entity);
        return $entity;
    }

    protected function getPreLoginSession($uid, $deviceType) {

        $preMap = ['login_device_type' => $deviceType, 'uid' => $uid];
        $result = $this->repo->findOneBy($preMap, ["expire_time" => "asc"]);
        if (!$result instanceof LoginSession) {
            unset($preMap['login_device_type']);
            $result = $this->repo->findOneBy($preMap, ["expire_time" => "asc"]);
        }
        return $result;
    }

    /**
     *
     * @param $uid
     * @param $s_id
     * @return bool|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function logout($uid, $s_id)
    {
        $loginSession = $this->repo->findOneBy(['uid' => $uid, 'login_session_id' => $s_id]);
        if ($loginSession instanceof LoginSession) {
            $loginSession->setExpireTime(0);
            $loginSession->setLoginSessionId('DEL_' . $loginSession->getLoginSessionId());
            $this->repo->flush();
            return 0;
        }
        return -1;
    }

}
