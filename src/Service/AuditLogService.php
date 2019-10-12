<?php


namespace App\Service;


use App\Entity\AuditLog;
use App\Entity\UserProfile;
use App\Repository\AuditLogRepository;
use App\ServiceInterface\AuditLogServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;


class AuditLogService extends BaseService implements AuditLogServiceInterface
{
    protected $userProfileRepo;

    public function __construct(AuditLogRepository $repository, UserProfileServiceInterface $userProfile)
    {
        $this->repo = $repository;
        $this->userProfileRepo = $userProfile;
    }

    /**
     * @param $content
     * @param $auditUid
     * @param string $auditNick
     * @param int $objectId
     * @param string $objectType
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function log($content, $auditUid, $auditNick = '', $objectId = 0, $objectType = '') {
        if (empty($auditNick)) {
            $userProfile = $this->userProfileRepo->info(['user' => $auditUid]);
            if ($userProfile instanceof  UserProfile) {
                $auditNick = $userProfile->getNickname();
            } else {
                $auditNick = 'Unknown';
            }
        }

        $log = new AuditLog();
        $log->setContent($content);
        $log->setAuditUid($auditUid);
        $log->setAuditNick($auditNick);
        $log->setObjectId($objectId);
        $log->setObjectType($objectType);

        return $this->add($log);
    }
}
