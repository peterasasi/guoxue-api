<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\UserProfileRepository;
use Dbh\SfCoreBundle\Common\BaseService;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;


class UserProfileService extends BaseService implements UserProfileServiceInterface
{
    /**
     * @var UserProfileRepository
     */
    protected $repo;

    public function __construct(UserProfileRepository $repository)
    {
        $this->repo = $repository;
    }



}
