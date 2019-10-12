<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\AlbumPhotoRepository;
use App\ServiceInterface\AlbumPhotoServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class AlbumPhotoService extends BaseService implements AlbumPhotoServiceInterface
{

    public function __construct(AlbumPhotoRepository $albumPhotoRepository)
    {
        $this->repo = $albumPhotoRepository;
    }
}
