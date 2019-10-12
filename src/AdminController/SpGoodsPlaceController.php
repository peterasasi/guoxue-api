<?php


namespace App\AdminController;


use App\Entity\SpGoods;
use App\Entity\SpGoodsPlace;
use App\Entity\SpGoodsSku;
use App\Helper\CodeGenerator;
use App\ServiceInterface\DatatreeServiceInterface;
use App\ServiceInterface\SpCateServiceInterface;
use App\ServiceInterface\SpGoodsPlaceServiceInterface;
use App\ServiceInterface\SpGoodsServiceInterface;
use App\ServiceInterface\SpGoodsSkuServiceInterface;
use App\ServiceInterface\SpPropertyValueServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpGoodsPlaceController extends BaseNeedLoginController
{
    protected $goodsService;
    protected $goodsPlaceService;

    public function __construct(
        SpGoodsServiceInterface $goodsService,
        SpGoodsPlaceServiceInterface $goodsPlaceService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->goodsPlaceService = $goodsPlaceService;
        $this->goodsService = $goodsService;
    }

    public function query($goodsId) {
        $placeList = $this->goodsPlaceService->queryAllBy(['goods' => $goodsId]);
        return $placeList;
    }

    public function create($goodsId, $freightTplId, $provinceCode, $provinceName,
                           $cityCode, $cityName, $areaCode, $areaName, $townCode,
                           $townName, $countryCode, $countryName) {
        $goods = $this->goodsService->findById($goodsId);
        if (!$goods instanceof SpGoods) return 'Invalid id';
        $place = new SpGoodsPlace();
        $place->setFreightTplId($freightTplId);
        $place->setProvinceName($provinceName);
        $place->setProvinceCode($provinceCode);
        $place->setCityName($cityName);
        $place->setCityCode($cityCode);
        $place->setAreaName($areaName);
        $place->setAreaCode($areaCode);
        $place->setTownName($townName);
        $place->setTownCode($townCode);
        $place->setCountryName($countryName);
        $place->setCountryCode($countryCode);
        $place->setGoods($goods);

        $this->goodsPlaceService->add($place);
        return CallResultHelper::success($place->getId());
    }

    public function update($id, $freightTplId, $provinceCode, $provinceName,
                           $cityCode, $cityName, $areaCode, $areaName, $townCode,
                           $townName, $countryCode, $countryName) {
        $place = $this->goodsPlaceService->findById($id);
        if (!$place instanceof SpGoodsPlace) return 'Invalid id';
        $place->setFreightTplId($freightTplId);
        $place->setProvinceName($provinceName);
        $place->setProvinceCode($provinceCode);
        $place->setCityName($cityName);
        $place->setCityCode($cityCode);
        $place->setAreaName($areaName);
        $place->setAreaCode($areaCode);
        $place->setTownName($townName);
        $place->setTownCode($townCode);
        $place->setCountryName($countryName);
        $place->setCountryCode($countryCode);
        $this->goodsPlaceService->flush($place);
        return CallResultHelper::success($place->getId());
    }

    public function delete($id) {
        $place = $this->goodsPlaceService->findById($id);
        if ($place) {
            $this->goodsPlaceService->delete($place);
        }
        return CallResultHelper::success();
    }
}
