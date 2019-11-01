<?php


namespace App\Service;


use App\Repository\XftMerchantRepository;
use App\ServiceInterface\XftMerchantServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class XftMerchantService extends BaseService implements XftMerchantServiceInterface
{
    public function __construct(XftMerchantRepository $repository)
    {
        $this->repo = $repository;
    }
}
