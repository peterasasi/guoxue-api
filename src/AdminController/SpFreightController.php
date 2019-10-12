<?php


namespace App\AdminController;


use App\Entity\SpFreight;
use App\ServiceInterface\SpFreightServiceInterface;
use App\ServiceInterface\SpGoodsPlaceServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpFreightController extends BaseNeedLoginController
{
    protected $service;
    protected $goodsPlaceService;

    public function __construct(
        SpGoodsPlaceServiceInterface $goodsPlaceService,
        SpFreightServiceInterface $freightService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->service = $freightService;
        $this->goodsPlaceService = $goodsPlaceService;
    }

    public function query(PagingParams $pagingParams) {
        $this->checkLogin();
        return $this->service->queryBy(['uid' => $this->getUid()], $pagingParams, ["id" => "desc"]);
    }

    public function create($name, $enableFreeCond, $freeCondition, $freightType, $method, $priceDefine, $logisticsType) {
        $this->checkLogin();
        $freight = new SpFreight();
        $freight->setEnableFreeCond(intval($enableFreeCond) === 1 ? 1 : 0);
        $freight->setFreeCondition($freeCondition);
        $freight->setUid($this->getUid());
        $freight->setName($name);
        $freight->setMethod($method);
        $freight->setFreightType($freightType);
        $freight->setPriceDefine($priceDefine);
        $freight->setLogisticsType($logisticsType);
        $this->service->add($freight);
        return CallResultHelper::success($freight->getId());
    }

    public function update($id, $name, $enableFreeCond, $freeCondition, $freightType, $method, $priceDefine, $logisticsType) {
        $this->checkLogin();

        $freight = $this->service->findById($id);
        if (!($freight instanceof SpFreight)) {
            return 'Invalid Id';
        }

        $freight->setEnableFreeCond(intval($enableFreeCond) === 1 ? 1 : 0);
        $freight->setFreeCondition($freeCondition);
        $freight->setFreightType($freightType);
        $freight->setName($name);
        $freight->setMethod($method);
        $freight->setPriceDefine($priceDefine);
        $freight->setLogisticsType($logisticsType);
        $this->service->flush($freight);
        return CallResultHelper::success($freight->getId());
    }

    /**
     * 删除
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $freight = $this->service->findById($id);
        if (!($freight instanceof SpFreight)) {
            return CallResultHelper::success();
        }

        $cnt = $this->goodsPlaceService->count(['freight_tpl_id' => $id]);
        if ($cnt > 0) {
            return CallResultHelper::fail('该模板正在使用中,请去除关联的商品后再删除');
        }

        $this->service->delete($freight);
        return CallResultHelper::success();
    }

}
