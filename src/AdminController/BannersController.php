<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\Banners;
use App\Entity\Datatree;
use App\Helper\SystemDtCode;
use App\ServiceInterface\BannersServiceInterface;
use App\ServiceInterface\DatatreeServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
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

    public function query(PagingParams $pagingParams, $userId = 0, $position = 0, $nowTime = 0) {
        $map = [];
        if (!empty($position)) {
            $map['position'] = $position;
        }
        if (!empty($userId)) {
            $map['uid'] = $userId;
        }
        if (!empty($nowTime)) {
            $map['start_time'] = ['lte', $nowTime];
            $map['end_time'] = ['gte', $nowTime];
        }
       // return $this->bannersService->queryAndCount($map, $pagingParams, ['sort' => 'desc']);
        $res = $this->bannersService->queryAndCount($map, $pagingParams, ['sort' => 'desc']);
        if ($res instanceof CallResult) {
            $res = $res->getData();
        }
        $count = $res['count'];
        $list = $res['list'];
        if(count($list) > 0){ // 获取图片所属位置
            foreach ($list as &$val){
                if($val['position'] > 0){
                    $map['code'] = $val['position'];
                    $alias = $this->dtService->info($map);
                    if (!($alias instanceof Datatree)) {
                        $val['alis'] = 'unknown';
                    } else {
                        $val['alis'] = $alias->getName();
                    }
                }
            }
            return CallResultHelper::success(['count' => $count, 'list' => $list]);
        }
        return $res;
    }

    public function createBy($title, $position, $userId, $jumpUrl, $jumpType, $imgUrl, $dateRange, $w = 100, $h = 100, $sort = 0) {
        if (!is_array($dateRange)) {
            return CallResultHelper::fail(['%param% invalid', ['%param%' => 'date_range']]);
        }
        $startTime = intval($dateRange[0] / 1000);
        $endTime = intval($dateRange[1] / 1000);
        if ($startTime > $endTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }
        return $this->create($title, $position, $userId, $w, $h, $jumpUrl, $jumpType, $imgUrl, $startTime, $endTime, $sort);
    }


    public function create($title, $position, $userId, $w, $h, $jumpUrl, $jumpType, $imgUrl, $startTime, $endTime, $sort = 0) {
        $entity = new Banners();
        $startTime = intval($startTime);
        $endTime = intval($endTime);
        if ($startTime > $endTime) {
            $tmp = $startTime;
            $startTime = $endTime;
            $endTime = $tmp;
        }
        if ($startTime < BY_APP_START_TIMESTAMP) {
            $startTime = BY_APP_START_TIMESTAMP;
        }
        $entity->setTitle($title);
        $entity->setH($h);
        $entity->setW($w);
        $entity->setUid($userId);
        $entity->setSort($sort);
        $entity->setStartTime($startTime);
        $entity->setEndTime($endTime);
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
     * 返回banner信息
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function bannerInfo($id)
    {
        $info = $this->bannersService->info(['id' => $id]);
        if(!($info instanceof  Banners))
        {
            return 'record not exists';
        }
        return $info;
    }
}
