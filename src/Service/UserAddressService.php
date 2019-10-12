<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\UserAddressRepository;
use App\ServiceInterface\UserAddressServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserAddressService extends BaseService implements UserAddressServiceInterface
{
    protected $repo;

    public function __construct(UserAddressRepository $repository)
    {
        $this->repo = $repository;
    }
}
