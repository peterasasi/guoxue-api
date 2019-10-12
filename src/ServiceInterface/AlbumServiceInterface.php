<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\ServiceInterface;


use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface AlbumServiceInterface extends BaseServiceInterface
{
    public function queryByTitle($title, PagingParams $pagingParams);
}
