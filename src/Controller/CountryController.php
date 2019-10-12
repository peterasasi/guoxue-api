<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\Country;
use App\ServiceInterface\CountryServiceInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class CountryController extends BaseSymfonyApiController
{
    protected $service;

    public function __construct(CountryServiceInterface $service, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->service = $service;
    }

    public function query($name = '') {
        $map = [];
        if (!empty($name)) {
            $map['name'] = ['like', '%'.$name.'%'];
        }
        return $this->service->queryAllBy($map, ["id" => "desc"]);
    }

    public function update($id, $name = '', $code = '', $py = '', $telPrefix = '') {
        $info = $this->service->info(['id' => $id]);
        if ($info instanceof Country) {
            $flag = 0;
            !empty($name) && $info->getName() != $name ? $info->setName($name) : $flag ++;
            !empty($code) && $info->getCode() != $code ? $info->setCode($code) : $flag ++;
            !empty($py) && $info->getPy() != $py ? $info->setPy($py) : $flag ++ ;
            !empty($telPrefix) && $info->getTelPrefix() != $telPrefix ? $info->setTelPrefix($telPrefix) : $flag ++ ;
            if ($flag > 0) {
                $this->service->flush($info);
            }
        }
        return $info;
    }
}
