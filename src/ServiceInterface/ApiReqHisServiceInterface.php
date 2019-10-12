<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/21
 * Time: 13:48
 */

namespace App\ServiceInterface;


use App\Entity\Clients;
use by\infrastructure\base\CallResult;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface ApiReqHisServiceInterface extends BaseServiceInterface
{
    /**
     * 检测 Clients 调用次数是否超过限制
     * @param Clients $clientInfo
     * @param $serviceType
     * @return CallResult
     */
    public function check(Clients $clientInfo, $serviceType);
}
