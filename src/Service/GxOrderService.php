<?php


namespace App\Service;


use App\Repository\GxOrderRepository;
use App\ServiceInterface\GxOrderServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class GxOrderService extends BaseService implements GxOrderServiceInterface
{
    public function __construct(GxOrderRepository $repository)
    {
        $this->repo = $repository;
    }
}
