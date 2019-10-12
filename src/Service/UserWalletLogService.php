<?php


namespace App\Service;


use App\Repository\UserWalletLogRepository;
use App\ServiceInterface\UserWalletLogServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserWalletLogService extends BaseService implements UserWalletLogServiceInterface
{
    public function __construct(UserWalletLogRepository $repository)
    {
        $this->repo = $repository;
    }
}
