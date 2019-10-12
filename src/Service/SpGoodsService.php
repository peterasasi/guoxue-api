<?php


namespace App\Service;


use App\Repository\SpGoodsRepository;
use App\ServiceInterface\SpGoodsServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpGoodsService extends BaseService implements SpGoodsServiceInterface
{
    public function __construct(SpGoodsRepository $repository)
    {
        $this->repo = $repository;
    }
}
