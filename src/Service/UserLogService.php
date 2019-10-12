<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 11:30
 */

namespace App\Service;


use App\Entity\UserLog;
use App\Repository\UserLogRepository;
use Dbh\SfCoreBundle\Common\BaseService;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;


class UserLogService extends BaseService implements UserLogServiceInterface
{
    /**
     * @var UserLogRepository
     */
    protected $repo;

    public function __construct(UserLogRepository $logRepository)
    {
        $this->repo = $logRepository;
    }

    /**
     * @param $uid
     * @param $note
     * @param $logType
     * @param $ip
     * @param $deviceType
     * @param $ua
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function log($uid, $note, $logType, $ip, $deviceType, $ua)
    {
        $entity = new UserLog();
        $entity->setUid($uid);
        $entity->setLogType($logType);
        $entity->setIp($ip);
        $entity->setDeviceType($deviceType);
        $entity->setUa($ua);
        $entity->setNote($note);
        return $this->repo->add($entity);
    }


}
