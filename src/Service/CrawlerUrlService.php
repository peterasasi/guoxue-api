<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\CrawlerUrlRepository;
use App\ServiceInterface\CrawlerUrlServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class CrawlerUrlService extends BaseService implements CrawlerUrlServiceInterface
{
    /**
     * @var CrawlerUrlRepository
     */
    protected $repo;

    public function __construct(CrawlerUrlRepository $repository)
    {
        $this->repo = $repository;
    }
}
