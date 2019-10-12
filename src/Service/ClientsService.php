<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/20
 * Time: 16:38
 */

namespace App\Service;


use App\Entity\Clients;
use App\Repository\ClientsRepository;
use App\ServiceInterface\ClientsServiceInterface;
use by\component\string_extend\helper\StringHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Dbh\SfCoreBundle\Common\ClientsInterface;
use Dbh\SfCoreBundle\Common\GetClientsInterface;


class ClientsService extends BaseService implements ClientsServiceInterface, GetClientsInterface
{
    function getClientBy($clientId): ?ClientsInterface
    {
        return $this->repo->findOneBy(['client_id' => $clientId]);
    }


    /**
     * @var ClientsRepository
     */
    protected $repo;

    public function __construct(ClientsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function add($entity, $noFlush = false)
    {
        if ($entity instanceof Clients) {
            $entity->setClientId($this->getClientId($entity->getUid()));
            $entity->setClientSecret($this->getClientSecretKey($entity->getUid()));
//            $info = $this->repo->findOneBy(['uid' => $entity->getUid()]);
//            if ($info) {
//                return "cant create more than one clients";
//            }
            return parent::add($entity, $noFlush);
        }
        return false;
    }

    public function getClientId($uid)
    {
        return 'by' . StringHelper::intTo62(1000000000 + intval($uid)) . StringHelper::intTo62(time());
    }

    public function getClientSecretKey($uid)
    {
        return md5(StringHelper::intTo62(intval($uid)) . StringHelper::intTo62(time()));
    }

    /**
     *
     * @param $uid
     * @param $id
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetClientSecretKey($id, $uid)
    {
        $newClientSecretKey = $this->getClientSecretKey($uid);
        return $this->updateOne(['uid' => $uid, 'id' => $id], ['client_secret' => $newClientSecretKey]);
    }
}
