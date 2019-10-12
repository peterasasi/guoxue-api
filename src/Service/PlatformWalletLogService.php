<?php


namespace App\Service;


use App\Repository\PlatformWalletLogRepository;
use App\ServiceInterface\PlatformWalletLogServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class PlatformWalletLogService extends BaseService implements PlatformWalletLogServiceInterface
{
    public function __construct(PlatformWalletLogRepository $repository)
    {
        $this->repo = $repository;
    }
}
