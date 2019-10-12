<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\SpBrandRepository;
use App\ServiceInterface\SpBrandServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpBrandService extends BaseService implements SpBrandServiceInterface
{
    public function __construct(SpBrandRepository $repository)
    {
        $this->repo = $repository;
    }
}
