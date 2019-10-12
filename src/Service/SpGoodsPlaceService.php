<?php


namespace App\Service;


use App\Repository\SpGoodsPlaceRepository;
use App\ServiceInterface\SpGoodsPlaceServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpGoodsPlaceService extends BaseService implements SpGoodsPlaceServiceInterface
{
    public function __construct(SpGoodsPlaceRepository $repository)
    {
        $this->repo = $repository;
    }
}
