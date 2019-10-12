<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/20
 * Time: 16:36
 */

namespace App\AdminController;


use App\Entity\Clients;
use App\ServiceInterface\ClientsServiceInterface;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use by\component\encrypt\constants\TransportEnum;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class ClientsController extends BaseNeedLoginController
{
    /**
     * @var ClientsServiceInterface
     */
    protected $service;

    public function __construct(UserAccountServiceInterface $userAccountService, KernelInterface $kernel, LoginSessionInterface $loginSession, ClientsServiceInterface $clientsService)
    {
        $this->service = $clientsService;
        parent::__construct($userAccountService, $loginSession, $kernel);
    }

    /**
     * 当前信息
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function info() {
        $this->checkLogin();
        $info = $this->service->info(['uid' => $this->getUid()]);
        return $info;
    }


    /**
     * @param $uid
     * @param $clientName
     * @param string $mAlg
     * @param string $mProjectId
     * @return mixed
     */
    public function create($uid, $clientName, $mAlg = TransportEnum::Nothing, $mProjectId = '') {
        if (empty($mProjectId)) {
            $mProjectId = 'P'.$uid;
        }
        $entity = new Clients();
        $entity->setUid($uid);
        $entity->setClientName($clientName);
        $entity->setApiAlg($mAlg);
        $entity->setProjectId($mProjectId);
        $entity->setDayLimit(10000);
        $entity->setTotalLimit(0);
        return $this->service->add($entity);
    }

    /**
     * 查询
     * @param $uid
     * @param PagingParams $pagingParams
     * @return mixed
     */
    public function query($uid, PagingParams $pagingParams)
    {
        $map = [
            'uid' => $uid
        ];

        return $this->service->queryBy($map, $pagingParams, ["id"=>"desc"]);
    }

    /**
     * 重置密钥
     * @param $id
     * @return string
     * @throws \by\component\exception\NotLoginException
     */
    public function resetClientSecretKey($id) {
        $this->checkLogin();
        $info = $this->service->resetClientSecretKey($id, $this->getUid());
        if ($info instanceof Clients) {
            return CallResultHelper::success($info->getClientSecret());
        }
        return CallResultHelper::fail('fail');
    }

    /**
     * @param $uid
     * @param $mClientId
     * @param $clientName
     * @param string $mProjectId
     * @param int $dayLimit
     * @param int $totalLimit
     * @param string $alg
     * @return null|object
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($uid, $mClientId, $clientName, $mProjectId = '', $dayLimit = -1, $totalLimit = -1, $mAlg = '') {
        $this->checkLogin();
        $update = [
            'client_name' => $clientName,
        ];
        if (!empty($mProjectId)) {
            $update['project_id'] = $mProjectId;
        }
        if (!empty($mAlg)) {
            $update['api_alg'] = $mAlg;
        }

        if ($dayLimit >= 0) {
            $update['day_limit'] = $dayLimit;
        }

        if ($totalLimit >= 0) {
            $update['total_limit'] = $totalLimit;
        }
        return $this->service->updateOne(['uid' => $uid, 'client_id' => $mClientId], $update);
    }
}
