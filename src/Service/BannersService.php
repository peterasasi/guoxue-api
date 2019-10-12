<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\BannersRepository;
use App\ServiceInterface\BannersServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class BannersService extends BaseService implements BannersServiceInterface
{
    /**
     * @var BannersRepository
     */
    protected $repo;

    public function __construct(BannersRepository $repository)
    {
        $this->repo = $repository;
    }
}
