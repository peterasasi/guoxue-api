<?php


namespace App\ServiceInterface;


use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface XftMerchantServiceInterface extends BaseServiceInterface
{
    public function incFailCnt($id);

    public function incSucCnt($id);
}
