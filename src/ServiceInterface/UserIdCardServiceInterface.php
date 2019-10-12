<?php


namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface UserIdCardServiceInterface extends BaseServiceInterface
{
    public function verifiedIdCard($userId);
}
