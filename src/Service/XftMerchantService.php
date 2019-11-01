<?php


namespace App\Service;


use App\Entity\XftMerchant;
use App\Repository\XftMerchantRepository;
use App\ServiceInterface\XftMerchantServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class XftMerchantService extends BaseService implements XftMerchantServiceInterface
{
    public function __construct(XftMerchantRepository $repository)
    {
        $this->repo = $repository;
    }

    public function incFailCnt($id)
    {
        $xftMerchant = $this->repo->find($id);
        if ($xftMerchant instanceof XftMerchant) {
            $xftMerchant->setFailCnt($xftMerchant->getFailCnt() + 1);
            $this->flush($xftMerchant);
        }
    }

    public function incSucCnt($id)
    {
        $xftMerchant = $this->repo->find($id);
        if ($xftMerchant instanceof XftMerchant) {
            $xftMerchant->setSucCount($xftMerchant->getSucCount() + 1);
            $this->flush($xftMerchant);
        }
    }


}
