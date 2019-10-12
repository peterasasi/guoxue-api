<?php


namespace App\Service;


use App\Repository\UserIdCardRepository;
use App\ServiceInterface\UserIdCardServiceInterface;
use by\component\audit_log\AuditStatus;
use Dbh\SfCoreBundle\Common\BaseService;


class UserIdCardService extends BaseService implements UserIdCardServiceInterface
{
    public function __construct(UserIdCardRepository $repository)
    {
        $this->repo = $repository;
    }

    public function verifiedIdCard($userId)
    {
        return $this->info(['uid' => $userId, 'verify' => AuditStatus::Passed]);
    }
}
