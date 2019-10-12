<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\Banners;
use App\Helper\SystemDtCode;
use App\ServiceInterface\BannersServiceInterface;
use App\ServiceInterface\DatatreeServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class BannersController extends BaseSymfonyApiController
{
    protected $bannersService;

    /**
     * @var DatatreeServiceInterface
     */
    protected $dtService;

    public function __construct(DatatreeServiceInterface $datatreeService, BannersServiceInterface $bannersService, KernelInterface $kernel)
    {
        $this->bannersService = $bannersService;
        $this->dtService = $datatreeService;
        parent::__construct($kernel);
    }

    public function queryPosition() {
        return $this->dtService->queryAllBy(['code' => ['like', SystemDtCode::BannersPosition.'___']]);
    }

    public function query(PagingParams $pagingParams, $position = 0) {
        $map = [];
        if (!empty($position)) {
            $map['position'] = $position;
        }
        return $this->bannersService->queryAndCount($map, $pagingParams, ['sort' => 'desc']);

    }

    public function createBy($title, $position, $userId, $jumpUrl, $jumpType, $imgUrl, $dateRange, $w = 100, $h = 100, $sort = 0) {
        if (!is_array($dateRange)) {
            return CallResultHelper::fail(['%param% invalid', ['%param%' => 'date_range']]);
        }
        $startTime = intval($dateRange[0]) / 1000;
        $endTime = intval($dateRange[1]) / 1000;
        if ($startTime > $endTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }
        return $this->create($title, $position, $userId, $w, $h, $jumpUrl, $jumpType, $imgUrl, $startTime, $endTime, $sort);
    }


    public function create($title, $position, $userId, $w, $h, $jumpUrl, $jumpType, $imgUrl, $startTime, $endTime, $sort = 0) {
        $entity = new Banners();
        $entity->setTitle($title);
        $entity->setH($h);
        $entity->setW($w);
        $entity->setUid($userId);
        $entity->setSort($sort);
        $entity->setStartTime(intval($startTime));
        $entity->setEndTime(intval($endTime));
        $entity->setJumpType($jumpType);
        $entity->setJumpUrl($jumpUrl);
        $entity->setImgUrl($imgUrl);
        $entity->setPosition($position);

        return $this->bannersService->add($entity);
    }

    /**
     * @param $id
     * @param $title
     * @param $position
     * @param $userId
     * @param $w
     * @param $h
     * @param $jumpUrl
     * @param $jumpType
     * @param $imgUrl
     * @param $startTime
     * @param $endTime
     * @param int $sort
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $position, $userId, $jumpUrl, $jumpType, $imgUrl, $startTime, $endTime, $w = 100, $h = 100,  $sort = 0) {

        if ($startTime > $endTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }

        $entity = $this->bannersService->info(['id' => $id, 'uid' => $userId]);
        if (!($entity instanceof Banners)) {
            return 'record not exists';
        }
        $entity->setTitle($title);
        $entity->setH(intval($h));
        $entity->setW(intval($w));
        $entity->setUid(intval($userId));
        $entity->setSort(intval($sort));
        $entity->setStartTime(intval($startTime));
        $entity->setEndTime(intval($endTime));
        $entity->setJumpType($jumpType);
        $entity->setJumpUrl($jumpUrl);
        $entity->setImgUrl($imgUrl);
        $entity->setPosition($position);

        return $this->bannersService->flush($entity);
    }

    public function delete($id) {
        $entity = $this->bannersService->info(['id' => $id]);
        if ($entity instanceof Banners) {
            $this->bannersService->delete($entity);
        }
        return CallResultHelper::success();
    }

    /**
     * 广告图点击量加1
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function bannerClick($id)
    {
        $entity = $this->bannersService->info(['id' => $id]);
        if (!($entity instanceof Banners)) {
            return 'record not exists';
        }
        $getNum = $entity->getClickNums();
        $entity->setClickNums($getNum + 1);
        return CallResultHelper::success($this->bannersService->flush($entity));
    }
}
