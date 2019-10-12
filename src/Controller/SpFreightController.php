<?php


namespace App\Controller;


use App\Entity\SpFreight;
use App\ServiceInterface\SpFreightServiceInterface;
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

    public function __construct(
        SpFreightServiceInterface $freightService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->service = $freightService;
    }

    public function index(PagingParams $pagingParams) {
        $this->checkLogin();
        return $this->service->queryBy(['uid' => $this->getUid()], $pagingParams, ["id" => "desc"]);
    }

    public function create($name, $method, $priceDefine, $logisticsType) {
        $this->checkLogin();
        $freight = new SpFreight();
        $freight->setUid($this->getUid());
        $freight->setName($name);
        $freight->setMethod($method);
        $freight->setPriceDefine($priceDefine);
        $freight->setLogisticsType($logisticsType);
        $this->service->add($freight);
        return CallResultHelper::success($freight->getId());
    }

    public function update($id, $name, $method, $priceDefine, $logisticsType) {
        $this->checkLogin();

        $freight = $this->service->findById($id);
        if (!($freight instanceof SpFreight)) {
            return 'Invalid Id';
        }
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
        $this->service->delete($freight);
        return CallResultHelper::success();
    }

}
