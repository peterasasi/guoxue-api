<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\SpPropertyRepository;
use App\ServiceInterface\SpPropertyServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpPropertyService extends BaseService implements SpPropertyServiceInterface
{
    public function __construct(SpPropertyRepository $repository)
    {
        $this->repo = $repository;
    }
}
