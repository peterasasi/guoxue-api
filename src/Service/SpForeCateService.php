<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\SpForeCateRepository;
use App\ServiceInterface\SpForeCateServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpForeCateService extends BaseService implements SpForeCateServiceInterface
{
    public function __construct(SpForeCateRepository $repository)
    {
        $this->repo = $repository;
    }
}
