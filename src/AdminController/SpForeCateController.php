<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\SpBrand;
use App\Entity\SpCate;
use App\Entity\SpForeCate;
use App\Entity\SpGoods;
use App\Entity\SpProperty;
use App\Entity\SpPropertyValue;
use App\ServiceInterface\SpBrandServiceInterface;
use App\ServiceInterface\SpCateServiceInterface;
use App\ServiceInterface\SpForeCateServiceInterface;
use App\ServiceInterface\SpGoodsServiceInterface;
use App\ServiceInterface\SpPropertyServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Component\HttpKernel\KernelInterface;

class SpForeCateController extends BaseNeedLoginController
{
    protected $foreCateService;
    protected $goodsService;

    public function __construct(
        SpForeCateServiceInterface $foreCateService, SpGoodsServiceInterface $goodsService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->foreCateService = $foreCateService;
        $this->goodsService = $goodsService;
    }

    public function info($id)
    {

        $ret = [
            'parent_id' => -1,
            'uid' => 0,
            'id' => -1,
            'title' => '',
        ];
        if ($id > 0) {
            $foreCate = $this->foreCateService->findById($id);
            if ($foreCate instanceof SpForeCate) {
                $ret = [
                    'uid' => $foreCate->getUid(),
                    'parent_id' => $foreCate->getParentId(),
                    'id' => $foreCate->getId(),
                    'title' => $foreCate->getTitle(),
                ];
            }
        }
        return CallResultHelper::success($ret);
    }

    /**
     * 创建类目
     * @param $title
     * @param int $leaf
     * @param int $sort
     * @param int $parentId
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     */
    public function create($title, $leaf = 0, $sort = 0, $parentId = 0)
    {
        $this->checkLogin();
        $entity = new SpForeCate();
        $entity->setUid($this->getUid());
        $entity->setTitle($title);
        $entity->setLeaf($leaf);
        $entity->setLevel(0);
        $entity->setSort($sort);
        $entity->setParentId(0);
        $entity->setStatus(StatusEnum::ENABLE);
        if (intval($parentId) > 0) {
            $parentCate = $this->foreCateService->findById($parentId);
            if ($parentCate instanceof SpForeCate) {
                if ($parentCate->getLevel() >= 3) {
                    return 'cant add sub category';
                }
                if ($parentCate->getLevel() == 2) {
                    $entity->setLeaf(1);
                }
                $entity->setLevel($parentCate->getLevel() + 1);
                $entity->setParentId($parentCate->getId());
            }
        }
        $this->foreCateService->add($entity);
        return CallResultHelper::success($entity->getId());
    }

    /**
     * @param $id
     * @param $title
     * @param int $leaf
     * @param int $sort
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $leaf = 0, $sort = 0)
    {
        $cate = $this->foreCateService->findById($id);
        if (!($cate instanceof SpForeCate)) {
            return 'invalid id';
        }
        $cate->setTitle($title);
        if ($cate->getSort() != intval($sort)) {
            $cate->setSort($sort);
        }
        $cate->setLeaf($leaf);
        $this->foreCateService->flush($cate);
        return CallResultHelper::success($cate->getId());
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $cate = $this->foreCateService->findById($id);
        if (!($cate instanceof SpForeCate)) {
            return 'invalid id';
        }
        $cate->setStatus(StatusEnum::SOFT_DELETE);
        $this->foreCateService->flush($cate);
        return CallResultHelper::success();
    }

    /**
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     */
    public function query3Level() {
        $this->checkLogin();
        $map = ['uid' => $this->getUid(), 'status' => StatusEnum::ENABLE, 'level' => 0];
        $maxLevel = 3;
        $level = 0;
        $allList = [];
        while ($level < $maxLevel) {
            $map['level'] = $level;
            $allList[$level] = $this->foreCateService->queryAllBy($map, ['sort' => "desc"], ["id", "parentId", "title", "leaf", "level"]);
            $level++;
        }
        $level = $maxLevel;
        while ($level - 2 >= 0) {
            foreach ($allList[$level-2] as &$level2) {
                $level2['children'] = [];
                foreach ($allList[$level - 1] as $level3) {
                    if ($level3['parent_id'] == $level2['id']) {
                        array_push($level2['children'], $level3);
                    }
                }
            }
            $level--;
        }

        return CallResultHelper::success($allList[0]);
    }

    /**
     * @param int $parentId
     * @param string $title
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query($parentId = 0, $title = '')
    {
        $this->checkLogin();
        $map = ['uid' => $this->getUid(), 'status' => StatusEnum::ENABLE, 'parent_id' => $parentId];
        if (!empty($title)) {
            $map['title'] = ['like', '%' . $title . '%'];
        }
        return $this->foreCateService->queryAllBy($map, ['id' => 'asc', 'sort' => 'desc']);
    }


    public function relateGoods($goodsId, $foreCateId) {
        $cate = $this->foreCateService->findById($foreCateId);
        if ($cate instanceof SpForeCate) {
            $goods = $this->goodsService->findById($goodsId);
            if ($goods instanceof SpGoods) {
                $cate->addGood($goods);
//                $goods->addForeCates($cate);
                $this->foreCateService->flush($cate);
//                $this->goodsService->flush($goods);
                return CallResultHelper::success();
            }
        }
        return CallResultHelper::fail();
    }

    public function queryGoods(PagingParams $pagingParams, $title, $foreCateId, $shelfStatus = 0) {
        $cate = $this->foreCateService->findById($foreCateId);
        if ($cate instanceof SpForeCate) {
            $criteria = Criteria::create()
                ->orderBy(array("sales" => Criteria::DESC))
                ->setFirstResult($pagingParams->offset())
                ->setMaxResults($pagingParams->getPageSize());

            $cp = new Comparison('status', '=', new Value('1'));
            $criteria->where($cp);
            $cp = new Comparison('shelfStatus', '=', new Value($shelfStatus));
            $criteria->where($cp);
            if (!empty($title)) {
                $cp = new Comparison('title', 'like', new Value('%' . $title . '%'));
                $criteria->where($cp);
            }
            $queryResult = $cate->getGoods()->matching($criteria)->map(function (SpGoods $item) use($cate) {
                return [
                    'id' => $item->getId(),
                    'uid' => $item->getUid(),
                    'title' => $item->getTitle(),
                    'sub_title' => $item->getSubTitle(),
                    'show_price' => $item->getShowPrice(),
                    'create_time' => $item->getCreateTime(),
                    'cover' => $item->getCoverImg(),
                    'small_cover' => $item->getSmallCoverImg(),
                    'monthly_sales' => $item->getMonthlySales(),
                    'sales' => $item->getSales(),
                    'sale_open_time' => $item->getSaleOpenTime(),
                    'sale_end_time' => $item->getSaleEndTime(),
                    'fore_cate_name' => $cate->getTitle()
                ];
            });
            return CallResultHelper::success($queryResult);
        }
        return CallResultHelper::fail('invalid fore_cate_id');
    }
}
