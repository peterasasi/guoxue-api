<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\CrawlerUrl;
use App\ServiceInterface\CrawlerUrlServiceInterface;
use by\component\paging\vo\PagingParams;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class CrawlerUrlController extends BaseSymfonyApiController
{
    /**
     * @var CrawlerUrlServiceInterface
     */
    protected $crawlerService;

    public function __construct(CrawlerUrlServiceInterface $crawlerUrlService, KernelInterface $kernel)
    {
        $this->crawlerService = $crawlerUrlService;
        parent::__construct($kernel);
    }

    /**
     * @param $urlType
     * @param $url
     * @param int $status
     * @param PagingParams $pagingParams
     * @return mixed
     */
    public function query($urlType, $url, PagingParams $pagingParams, $status = -2) {
        $map = [];
        if (!empty($urlType)) {
            $map['url_type'] = $urlType;
        }
        if (!empty($url)) {
            $map['url'] = ['like', '%'.$url.'%'];
        }
        if (intval($status) !== -2) {
            $map['status'] = $status;
        }

        return $this->crawlerService->queryAndCount($map, $pagingParams, ['updateTime' => 'asc']);
    }

    /**
     * @param $url
     * @param $urlType
     * @return mixed
     */
    public function create($url, $urlType) {
        $crawler = new CrawlerUrl();
        $crawler->setStatus(0);
        $crawler->setUrlType($urlType);
        $crawler->setUrl($url);
        $crawler->setUpdateTime(time());
        return $this->crawlerService->add($crawler);
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update($id, $status) {
        $crawler = $this->crawlerService->info(['id' => $id]);
        if ($crawler instanceof CrawlerUrl) {
            $crawler->setStatus($status);
        }
        $this->crawlerService->flush($crawler);
        return $crawler;
    }
}
