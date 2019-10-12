<?php


namespace App\AdminController;


use App\Entity\SpGoods;
use App\Entity\SpShop;
use App\ServiceInterface\SpGoodsServiceInterface;
use App\ServiceInterface\SpShopServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpKernel\KernelInterface;

class SpShopController extends BaseNeedLoginController
{
    protected $shopService;
    protected $goodsService;

    public function __construct(
        SpGoodsServiceInterface $goodsService,
        SpShopServiceInterface $service, UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->shopService = $service;
        $this->goodsService = $goodsService;
    }

    /**
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query() {
        $this->checkLogin();
        return $this->shopService->queryAllBy(['status' => StatusEnum::ENABLE,'uid' => $this->getUid()]);
    }

    /**
     * @param $title
     * @param $description
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     */
    public function create($title, $description) {
        $this->checkLogin();
        $shop = new SpShop();
        $shop->setUid($this->getUid());
        $shop->setTitle($title);
        $shop->setDescription($description);
        $this->shopService->add($shop);
        return CallResultHelper::success($shop->getId());
    }

    /**
     * @param $id
     * @param $title
     * @param $description
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function update($id, $title, $description) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $shop->setTitle($title);
            $shop->setDescription($description);
            $this->shopService->flush($shop);
        }
        return CallResultHelper::success($shop->getId());
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function delete($id) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $shop->setStatus(StatusEnum::SOFT_DELETE);
            $this->shopService->flush($shop);
        }
        return CallResultHelper::success($shop->getId());
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function close($id) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $shop->setClosed(1);
            $this->shopService->flush($shop);
        }
        return CallResultHelper::success($shop->getId());
    }

    /**
     * 店铺营业中
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function opening($id) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $shop->setClosed(0);
            $this->shopService->flush($shop);
        }
        return CallResultHelper::success($shop->getId());
    }

    /**
     * @param $id
     * @param $goodsId
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function addGoods($id, $goodsId) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $goods = $this->goodsService->findById($goodsId);
            if ($goods instanceof SpGoods) {
                if ($goods->getUid() != $shop->getUid()) {
                    return  CallResultHelper::fail('Goods and Shop must belong a same user');
                }
                $shop->addGoods($goods);
                $this->shopService->flush($shop);
                return CallResultHelper::success();
            }
            return CallResultHelper::fail('invalid goods_id');
        }
        return CallResultHelper::fail('invalid id');
    }

    /**
     * @param $id
     * @param $goodsId
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function removeGoods($id, $goodsId) {
        $this->checkLogin();
        $shop = $this->shopService->findById($id);
        if ($shop instanceof SpShop) {
            $goods = $this->goodsService->findById($goodsId);
            if ($goods instanceof SpGoods) {
                $shop->removeGoods($goods);
                $this->shopService->flush($shop);
                return CallResultHelper::success();
            }
            return CallResultHelper::fail('invalid goods_id');
        }
        return CallResultHelper::fail('invalid id');
    }

    /**
     * @param $shopId
     * @param PagingParams $pagingParams
     * @param $title
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     */
    public function queryGoods($shopId, PagingParams $pagingParams, $title) {
        $this->checkLogin();
        $shop = $this->shopService->findById($shopId);
        if (!$shop instanceof SpShop) return CallResultHelper::fail('invalid id');

        $criteria = Criteria::create()
            ->orderBy(array("id" => Criteria::ASC))
            ->setFirstResult($pagingParams->offset())
            ->setMaxResults($pagingParams->getPageSize());

        if (!empty($title)) {
            $cp = new Comparison('title', 'like', new Value('%'.$title.'%'));
            $criteria->where($cp);
            $count = $shop->getGoods()->matching(Criteria::create()->where($cp))->count();
        } else {
            $count = $shop->getGoods()->count();
        }
        $goodsList = $shop->getGoods();
        $list = [];
        if ($goodsList instanceof Selectable) {
            $list = $goodsList->matching($criteria)->map(function (SpGoods $item) use ($shop) {
                return [
                    'shop_name' => $shop->getTitle(),
                    'uid' => $shop->getUid(),
                    'shop_desc' => $shop->getDescription(),
                    'title' => $item->getTitle(),
                    'id' => $item->getId(),
                    'sales' => $item->getSales(),
                    'monthly_sales' => $item->getMonthlySales(),
                    'weight' => $item->getWeight(),
                    'volume' => $item->getVolume(),
                    'sub_title' => $item->getSubTitle(),
                    'cate_id' => $item->getCate()->getId(),
                    'cate_name' => $item->getCate()->getTitle(),
                    'sale_end_time' => $item->getSaleEndTime(),
                    'sale_open_time' => $item->getSaleOpenTime(),
                    'create_time' => $item->getCreateTime(),
                    'shelf_status' => $item->getShelfStatus(),
                    'cover_img' => $item->getCoverImg(),
                    'small_cover_img' => $item->getSmallCoverImg(),
                ];
            });
        }
        return CallResultHelper::success([
            'count' => $count,
            'list' => $list
        ]);
    }
}
