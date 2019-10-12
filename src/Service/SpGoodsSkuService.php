<?php


namespace App\Service;


use App\Repository\SpGoodsRepository;
use App\Repository\SpGoodsSkuRepository;
use App\ServiceInterface\SpGoodsSkuServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpGoodsSkuService extends BaseService implements SpGoodsSkuServiceInterface
{
    public function __construct(SpGoodsSkuRepository $repository)
    {
        $this->repo = $repository;
    }


}
