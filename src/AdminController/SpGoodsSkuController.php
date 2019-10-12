<?php


namespace App\AdminController;


use App\Entity\Datatree;
use App\Entity\SpCate;
use App\Entity\SpGoods;
use App\Entity\SpGoodsPlace;
use App\Entity\SpGoodsSku;
use App\Entity\SpProperty;
use App\Entity\SpPropertyValue;
use App\Helper\CodeGenerator;
use App\Repository\SpGoodsSkuRepository;
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
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class SpGoodsSkuController extends BaseNeedLoginController
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

    public function info($goodsId) {
        $goods = $this->goodsService->findById($goodsId);
        if (!$goods instanceof SpGoods) {
            return CallResultHelper::fail('goods id invalid');
        }

        $skuList =  $this->goodsSkuService->queryAllBy(['goods' => $goodsId], ["uniqGoodsNo" => 'desc']);
        foreach ($skuList as &$vo) {
            $vo['specs'] = json_decode($vo['specs'], JSON_OBJECT_AS_ARRAY);
        }
        return CallResultHelper::success($skuList);
    }

    /**
     * 删除多余的规格
     * @param $goodsId
     * @param $uniqGoodsNo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteBy($goodsId, $uniqGoodsNo) {
        $skuList = $this->goodsSkuService->queryAllBy(['goods' => $goodsId], ["id" => 'desc']);
        if (is_array($uniqGoodsNo) && count($uniqGoodsNo) > 0) {
            foreach ($skuList as $sku) {
                if (!in_array($sku['uniq_goods_no'], $uniqGoodsNo)) {
                    $this->goodsSkuService->deleteWhere(['id' => $sku['id']]);
                }
            }
        }
    }


    /**
     * @param $goodsId
     * @param $skuIndex
     * @param $pic
     * @param $singleSku
     * @param $specs
     * @param $stockPrice
     * @param $stock
     * @param $price
     * @param $uniqGoodsNo
     * @param $outGoodsNo
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($goodsId, $skuIndex, $pic, $singleSku, $specs, $stockPrice, $stock, $price, $uniqGoodsNo, $outGoodsNo) {
        $goods = $this->goodsService->findById($goodsId);
        if (!$goods instanceof SpGoods) {
            return CallResultHelper::fail('goods id invalid');
        }
        if ($goods->getShelfStatus() == 1) {
            return CallResultHelper::fail('请先下架商品后再编辑');
        }

        if (!(count($specs) === count($stockPrice) && count($specs) === count($price) && count($specs) === count($stock))) {
            return CallResultHelper::fail('请填写完全库存、价格、入库价格');
        }

        $cateId = $goods->getCate()->getId();
        $maxSku = $this->goodsSkuService->info(['goods' => $goodsId], ["uniqGoodsNo" => 'desc']);
        $postFix = 1;
        if ($maxSku instanceof SpGoodsSku) {
            $postFix = substr($maxSku->getUniqGoodsNo(), -3, 3);
            $postFix = CodeGenerator::char35ToInt(ltrim($postFix, '0'));
        }
        if (intval($singleSku) == 1 && count($specs) > 1) {
            return CallResultHelper::fail('单规格只能有一个规格');
        }

        $this->deleteBy($goodsId, $uniqGoodsNo);

        for ($i = 0 ; $i < count($specs); $i ++ ) {
            // 根据货号确定是否已存在sku
            $sku = $this->goodsSkuService->info(['uniq_goods_no' => $uniqGoodsNo[$i]]);
            if (!$sku instanceof SpGoodsSku) {
                $uniqCode = CodeGenerator::goodsSkuNo($cateId, $goodsId, $postFix++);
                $sku = new SpGoodsSku();
                $sku->setPic($pic[$i]);
                $sku->setSkuIndex($skuIndex[$i]);
                $sku->setSingleSku(intval($singleSku));
                $sku->setGoods($goods);
                $sku->setUniqGoodsNo($uniqCode);
                if (!array_key_exists($i, $outGoodsNo)) {
                    $sku->setGoodsNo('');
                } else {
                    $sku->setGoodsNo($outGoodsNo[$i]);
                }
                $sku->setSpecs($specs[$i]);
                $sku->setPrice(intval($price[$i]));
                $sku->setStock(intval($stock[$i]));
                $sku->setStockPrice(intval($stockPrice[$i]));
                $this->goodsSkuService->add($sku, false);
            } else {
                if ($sku->getSpecs() != $specs[$i]) {
                    // 规格定义变了，则货号也要重新生成
                    $uniqCode = CodeGenerator::goodsSkuNo($cateId, $goodsId, $postFix++);
                    $sku->setUniqGoodsNo($uniqCode);
                }
                $sku->setPic($pic[$i]);
                $sku->setSkuIndex($skuIndex[$i]);
                $sku->setGoodsNo($outGoodsNo[$i]);
                $sku->setSpecs($specs[$i]);
                $sku->setPrice(intval($price[$i]));
                $sku->setStock(intval($stock[$i]));
                $sku->setStockPrice(intval($stockPrice[$i]));
                $this->goodsSkuService->flush($sku);
            }
        }
        $this->goodsSkuService->flush();
        return CallResultHelper::success();
    }

    public function query($goodsId) {
        $skuList = $this->goodsSkuService->queryAllBy(['goods' => $goodsId]);
        return $skuList;
    }
}
