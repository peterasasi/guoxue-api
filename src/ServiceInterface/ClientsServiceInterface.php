<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/20
 * Time: 16:39
 */

namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;
interface ClientsServiceInterface extends BaseServiceInterface
{
    public function resetClientSecretKey($id, $uid);
}
