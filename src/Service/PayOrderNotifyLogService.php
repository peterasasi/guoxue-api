<?php


namespace App\Service;


use App\Repository\PayOrderNotifyLogRepository;
use App\ServiceInterface\PayOrderNotifyLogServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class PayOrderNotifyLogService extends BaseService implements PayOrderNotifyLogServiceInterface
{
    public function __construct(PayOrderNotifyLogRepository $repository)
    {
        $this->repo = $repository;
    }
}
