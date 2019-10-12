<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\SpPropertyValueRepository;
use App\ServiceInterface\SpPropertyValueServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class SpPropertyValueService extends BaseService implements SpPropertyValueServiceInterface
{
    public function __construct(SpPropertyValueRepository $repository)
    {
        $this->repo = $repository;
    }
}
