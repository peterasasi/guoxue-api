<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\SpCateRepository;
use App\ServiceInterface\SpCateServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpCateService extends BaseService implements SpCateServiceInterface
{
    public function __construct(SpCateRepository $repository)
    {
        $this->repo = $repository;
    }
}
