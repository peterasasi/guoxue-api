<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\AlbumCategoryRepository;
use App\ServiceInterface\AlbumCategoryServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class AlbumCategoryService extends BaseService implements AlbumCategoryServiceInterface
{

    public function __construct(AlbumCategoryRepository $albumCategoryRepository)
    {
        $this->repo = $albumCategoryRepository;
    }
}
