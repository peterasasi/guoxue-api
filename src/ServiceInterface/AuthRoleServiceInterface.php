<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 16:55
 */

namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;
use App\Entity\AuthPolicy;
use App\Entity\AuthRole;

interface AuthRoleServiceInterface extends BaseServiceInterface
{
    public function addPolicy(AuthRole $role, AuthPolicy $policy);
    public function removePolicy(AuthRole $role, AuthPolicy $policy);

}
