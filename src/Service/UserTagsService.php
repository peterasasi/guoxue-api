<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\UserTagsRepository;
use App\ServiceInterface\UserTagsServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserTagsService extends BaseService implements UserTagsServiceInterface
{
    /**
     * @var UserTagsRepository
     */
    protected $repo;

    public function __construct(UserTagsRepository $repository)
    {
        $this->repo = $repository;
    }
}
