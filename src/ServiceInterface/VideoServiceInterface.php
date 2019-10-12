<?php


namespace App\ServiceInterface;


use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface VideoServiceInterface extends BaseServiceInterface
{
    public function random($pageSize = 10);

    public function queryAdminWithTags($title, $categoryId, PagingParams $pagingParams, $status = StatusEnum::ENABLE);
}
