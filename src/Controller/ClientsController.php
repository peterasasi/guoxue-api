<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/20
 * Time: 16:36
 */

namespace App\Controller;


use App\Entity\Clients;
use App\ServiceInterface\ClientsServiceInterface;
use by\component\encrypt\rsa\Rsa;
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
     * @param $clientId
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function info($clientId)
    {
        $this->checkLogin();
        $info = $this->service->info(['uid' => $this->getUid(), 'client_id' => $clientId]);
        if ($info instanceof Clients) {
            $info->setSysPrivateKey('');
            $info->setUserPrivateKey('');
        }
        return $info;
    }


    /**
     * @param $uid
     * @param $clientName
     * @param string $mAlg
     * @param string $mProjectId
     * @return mixed
     */
    public function create($uid, $clientName, $mAlg = TransportEnum::Nothing, $mProjectId = '')
    {
        if (empty($mProjectId)) {
            $mProjectId = 'P' . $uid;
        }
        $entity = new Clients();
        $entity->setUid($uid);
        $entity->setClientName($clientName);
        $entity->setApiAlg($mAlg);
        $entity->setProjectId($mProjectId);
        $entity->setDayLimit(0);
        $entity->setTotalLimit(0);
        // 初始化
        list($userPubKey, $userPrivKey) = Rsa::generateRsaKeys();
        $entity->setUserPublicKey(Rsa::removeFormatPublicText($userPubKey));
        $entity->setUserPrivateKey(Rsa::removeFormatPrivateText($userPrivKey));
        list($pubKey, $privKey) = Rsa::generateRsaKeys();
        $entity->setSysPublicKey(Rsa::removeFormatPublicText($pubKey));
        $entity->setSysPrivateKey(Rsa::removeFormatPrivateText($privKey));
        return $this->service->add($entity);
    }

    /**
     * 查询
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query(PagingParams $pagingParams)
    {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];

        return $this->service->queryBy($map, $pagingParams, ["id" => "desc"], ["user_public_key", "sys_public_key", "total_limit", "day_limit", "create_time", "project_id", "api_alg", "client_secret", "client_id", "client_name", "id", "uid"]);
    }

    /**
     * 上传用户的公钥
     * @param $clientId
     * @param string $userPublicKey 一行字符串
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function uploadUserPublicKey($clientId, $userPublicKey)
    {
        $this->checkLogin();
        $map = ['client_id' => $clientId, 'uid' => $this->getUid()];
        $clientInfo = $this->service->info($map);
        if (!$clientInfo instanceof Clients) {
            return 'client id invalid';
        }

        $clientInfo->setUserPublicKey($userPublicKey);
        $this->service->flush($clientInfo);
        return CallResultHelper::success();
    }

    public function resetUserKey($clientId) {
        $clientInfo = $this->service->info(['client_id' => $clientId]);
        if (!$clientInfo instanceof Clients) return 'client id invalid';
        list($pubKey, $privKey) = Rsa::generateRsaKeys();
        $clientInfo->setUserPublicKey(Rsa::removeFormatPublicText($pubKey));
        $clientInfo->setUserPrivateKey(Rsa::removeFormatPrivateText($privKey));
        $this->service->flush($clientInfo);
        return $clientInfo->getUserPrivateKey();
    }

    public function resetSystemKey($clientId) {
        $clientInfo = $this->service->info(['client_id' => $clientId]);
        if (!$clientInfo instanceof Clients) return 'client id invalid';
        list($pubKey, $privKey) = Rsa::generateRsaKeys();
        $clientInfo->setSysPublicKey(Rsa::removeFormatPublicText($pubKey));
        $clientInfo->setSysPrivateKey(Rsa::removeFormatPrivateText($privKey));
        $this->service->flush($clientInfo);
        return $clientInfo->getSysPublicKey();
    }

    /**
     * 重置密钥
     * @param $id
     * @return string
     * @throws \by\component\exception\NotLoginException
     */
    public function resetClientSecretKey($id)
    {
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
    public function update($uid, $mClientId, $clientName, $mProjectId = '', $dayLimit = -1, $totalLimit = -1, $mAlg = '')
    {
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
