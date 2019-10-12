<?php


namespace App\AdminController;


use App\Entity\Datatree;
use App\Entity\SpCate;
use App\Entity\SpGoods;
use App\Entity\SpGoodsPlace;
use App\Entity\SpGoodsSku;
use App\Entity\SpPropertyValue;
use App\ServiceInterface\DatatreeServiceInterface;
use App\ServiceInterface\SpCateServiceInterface;
use App\ServiceInterface\SpGoodsPlaceServiceInterface;
use App\ServiceInterface\SpGoodsServiceInterface;
use App\ServiceInterface\SpGoodsSkuServiceInterface;
use App\ServiceInterface\SpPropertyValueServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use by\infrastructure\helper\Object2DataArrayHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class SpGoodsController extends BaseNeedLoginController
{
    protected $goodsService;
    protected $goodsPlaceService;
    protected $cateService;
    protected $propertyValueService;
    protected $goodsSkuService;
    protected $datatreeService;


    public function __construct(
        DatatreeServiceInterface $datatreeService,
        SpPropertyValueServiceInterface $propertyValueService,
        SpGoodsServiceInterface $goodsService,
        SpGoodsSkuServiceInterface $goodsSkuService,
        SpGoodsPlaceServiceInterface $goodsPlaceService,
        SpCateServiceInterface $cateService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->goodsService = $goodsService;
        $this->cateService = $cateService;
        $this->goodsPlaceService = $goodsPlaceService;
        $this->propertyValueService = $propertyValueService;
        $this->goodsSkuService = $goodsSkuService;
        $this->datatreeService = $datatreeService;
    }

    public function queryGoodsId($title = '') {
        $map = [
            'status' => ['neq', StatusEnum::SOFT_DELETE]
        ];
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title.'%'];
        }

        return $this->goodsService->queryBy($map, new PagingParams(0, 50), ["id" => "desc"]);
    }

    public function query(PagingParams $pagingParams, $title, $onShelf = 2) {
        $map = [
            'status' => ['neq', StatusEnum::SOFT_DELETE]
        ];
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title.'%'];
        }
        if ($onShelf == 0 || $onShelf == 1) {
            $map['shelf_status'] = $onShelf;
        }
        return $this->goodsService->queryAndCount($map, $pagingParams);
    }

    public function info($id) {
        $goods = $this->goodsService->findById($id);
        if (!$goods instanceof SpGoods) return 'invalid id';
        $properties = ['weight', 'volume', 'sale_open_time', 'sale_end_time', 'uid', 'img_list', 'cover_img', 'small_cover_img', 'id', 'title', 'subTitle', 'show_price'];

        $goodsArr = Object2DataArrayHelper::getDataArrayFrom($goods, $properties);
        $goodsArr['prop_values'] = $goods->getPropertyValues()->map(function (SpPropertyValue $item) {
            return [
                'prop_title' => $item->getProp()->getTitle(),
                'id' => $item->getId(),
                'title' => $item->getTitle()
            ];
        });
        $goodsArr['sku'] = $goods->getSpGoodsSkus()->map(function (SpGoodsSku $item) {
            $specs = json_decode($item->getSpecs(), JSON_OBJECT_AS_ARRAY);
            sort($specs);
            return [
                'id' => $item->getId(),
                'update_time' => $item->getUpdateTime(),
                'pic' => $item->getPic(),
                'uniq_goods_no' => $item->getUniqGoodsNo(),
                'specs' => $specs,
                'specs_index' => $item->getSkuIndex(),
                'goods_no' => $item->getGoodsNo(),
                'price' => $item->getPrice(),
                'single_sku' => $item->getSingleSku(),
                'stock' => $item->getStock(),
                'stock_price' => $item->getStockPrice(),
            ];
        });
        $goodsArr['cate_id'] = $goods->getCate()->getId();
        $goodsArr['support_service'] = $goods->getSupportServices()->map(function (Datatree $item) { return $item->getCode(); } );
        return $goodsArr;
    }

    public function update($id, $title, $weight, $volume, $propValueIds, $showPrice, $supportServices, $subTitle, $coverImg, $imgList, $smallCoverImg, $saleOpenTime, $saleEndTime) {
        $goods = $this->goodsService->findById($id);
        if (!$goods instanceof SpGoods) return 'invalid id';
        $goods->setWeight(StringHelper::numberFormat($weight, 3));
        $goods->setVolume(StringHelper::numberFormat($volume, 3));
        $goods->setTitle($title);
        $goods->setShelfStatus(0);
        $goods->setCoverImg($coverImg);
        $goods->setSmallCoverImg($smallCoverImg);
        $goods->setSaleOpenTime(intval($saleOpenTime));
        $goods->setSaleEndTime(intval($saleEndTime));
        $goods->setImgList($imgList);
        $goods->setSubTitle($subTitle);
        $goods->setShowPrice(intval($showPrice));
        $propValueIdArr = [];
        if (!empty($propValueIds)) {
            $propValueIdArr = explode(",", $propValueIds);
        }
        foreach ($propValueIdArr as $id) {
            $propValue = $this->propertyValueService->findById($id);
            if ($propValue instanceof SpPropertyValue) {
                $goods->addPropertyValue($propValue);
            }
        }
        // 标签、服务
        $service = [];
        if (!empty($supportServices)) {
            $service = explode(",", $supportServices);
        }
        foreach ($service as $code) {
            $dt = $this->datatreeService->info(['code' => $code]);
            if ($dt instanceof Datatree) {
                $goods->addSupportService($dt);
            }
        }

        $this->goodsService->flush($goods);
        return CallResultHelper::success();
    }

    public function create(Request $request, $weight, $volume, $propValueIds, $freightTplId, $freightType,
                           $title, $showPrice, $supportServices, $subTitle, $cateId, $coverImg,
                           $imgList, $smallCoverImg, $saleOpenTime, $saleEndTime) {
        $cate = $this->cateService->info(['id' => $cateId]);
        if (!$cate instanceof SpCate) {
            return 'cate_id invalid';
        }

        $goods = new SpGoods();
        $goods->setWeight(StringHelper::numberFormat($weight, 3));
        $goods->setVolume(StringHelper::numberFormat($volume, 3));
        $goods->setTitle($title);
        $goods->setCate($cate);
        $goods->setStatus(StatusEnum::ENABLE);
        $goods->setShelfStatus(0);
        $goods->setCoverImg($coverImg);
        $goods->setSmallCoverImg($smallCoverImg);
        $goods->setMonthlySales(0);
        $goods->setSales(0);
        $goods->setSaleOpenTime(intval($saleOpenTime));
        $goods->setSaleEndTime(intval($saleEndTime));
        $goods->setImgList($imgList);
        $goods->setSubTitle($subTitle);
        $goods->setShowPrice(intval($showPrice));
        $goods->setUid($this->getUid());

        $areaCode = $request->get('area_code', '');
        $areaName = $request->get('area_name', '');
        $countryCode = $request->get('country_code', '');
        $countryName = $request->get('country_name', '');
        $provinceCode = $request->get('province_code', '');
        $provinceName = $request->get('province_name', '');
        $cityCode = $request->get('city_code', '');
        $cityName = $request->get('city_name', '');
        $townCode = $request->get('town_code', '');
        $townName = $request->get('town_name', '');
        $freightType  = $request->get('freight_type', $freightType);
        $freightTplId  = $request->get('freight_tpl_id', $freightTplId);

        // 发货地
        $place = new SpGoodsPlace();
        $place->setAreaCode($areaCode);
        $place->setAreaName($areaName);
        $place->setCountryCode($countryCode);
        $place->setCountryName($countryName);
        $place->setProvinceCode($provinceCode);
        $place->setProvinceName($provinceName);
        $place->setCityCode($cityCode);
        $place->setCityName($cityName);
        $place->setTownCode($townCode);
        $place->setTownName($townName);
        $place->setFreightTplId($freightTplId);
        $place->setFreightType(intval($freightType));
        $propValueIdArr = [];
        if (!empty($propValueIds)) {
            $propValueIdArr = explode(",", $propValueIds);
        }
        foreach ($propValueIdArr as $id) {
            $propValue = $this->propertyValueService->findById($id);
            if ($propValue instanceof SpPropertyValue) {
                $goods->addPropertyValue($propValue);
            }
        }
        // 标签、服务
        $service = [];
        if (!empty($supportServices)) {
            $service = explode(",", $supportServices);
        }
        foreach ($service as $code) {
            $dt = $this->datatreeService->info(['code' => $code]);
            if ($dt instanceof Datatree) {
                $goods->addSupportService($dt);
            }
        }

        $this->goodsService->getEntityManager()->beginTransaction();
        try {
            $this->goodsService->add($goods);
            $place->setGoods($goods);
            $this->goodsPlaceService->add($place);
            $this->goodsService->getEntityManager()->commit();
            return CallResultHelper::success($goods->getId());
        } catch (\Exception $exception) {
            $this->goodsService->getEntityManager()->rollback();
            throw $exception;
        }
    }

    /**
     * 删除
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $goods = $this->goodsService->findById($id);
        if (!$goods instanceof SpGoods) {
            return CallResultHelper::success();
        }
        $goods->setStatus(StatusEnum::SOFT_DELETE);
        $this->goodsService->flush($goods);
        return CallResultHelper::success();
    }

    public function setShelfStatus($id, $status) {
        $goods = $this->goodsService->findById($id);
        if (!$goods instanceof SpGoods) {
            return CallResultHelper::fail('invalid id');
        }
        if ($status == 1) {
            // 检测商品是否设置了规格
            $cnt = $this->goodsPlaceService->count(['goods' => $id]);
            if ($cnt === 0 ) {
                return CallResultHelper::fail('请先设置商品发货地');
            }

            $cnt = $this->goodsSkuService->count(['goods' => $id]);
            if ($cnt === 0 ) {
                return CallResultHelper::fail('请先设置商品规格');
            }

            $goods->setShelfStatus(1);
        } else {
            $goods->setShelfStatus(0);
        }

        $this->goodsService->flush($goods);
        return CallResultHelper::success();
    }
}
