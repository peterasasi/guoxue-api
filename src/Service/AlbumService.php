<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\AlbumRepository;
use App\ServiceInterface\AlbumServiceInterface;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\BaseService;

class AlbumService extends BaseService implements AlbumServiceInterface
{

    public function __construct(AlbumRepository $albumRepository)
    {
        $this->repo = $albumRepository;
    }

    public function queryByTitle($title, PagingParams $pagingParams)
    {
        return $this->repo->findByTitle($title, $pagingParams);
    }
}
