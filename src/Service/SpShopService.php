<?php


namespace App\Service;


use App\Repository\SpShopRepository;
use App\ServiceInterface\SpShopServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpShopService extends BaseService implements SpShopServiceInterface
{
    public function __construct(SpShopRepository $repository)
    {
        $this->repo = $repository;
    }
}
