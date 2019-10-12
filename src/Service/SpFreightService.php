<?php


namespace App\Service;


use App\Repository\SpFreightRepository;
use App\ServiceInterface\SpFreightServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpFreightService extends BaseService implements SpFreightServiceInterface
{
    public function __construct(SpFreightRepository $repository)
    {
        $this->repo = $repository;
    }
}
