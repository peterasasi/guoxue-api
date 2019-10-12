<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\ReqPostRepository;
use App\ServiceInterface\ReqPostServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class ReqPostService extends BaseService implements ReqPostServiceInterface
{
    protected $repo;

    public function __construct(ReqPostRepository $repository)
    {
        $this->repo = $repository;
    }
}
