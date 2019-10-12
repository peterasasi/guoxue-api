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

interface CmsArticleServiceInterface extends BaseServiceInterface
{
    public function queryAdmin($title, $categoryId, PagingParams $pagingParams, $containDetail = 0, $status = '');
}
