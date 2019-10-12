<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\AlbumModelRepository;
use App\ServiceInterface\AlbumModelServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class AlbumModelService extends BaseService implements AlbumModelServiceInterface
{

    public function __construct(AlbumModelRepository $repository)
    {
        $this->repo = $repository;
    }


}
