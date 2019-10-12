<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/21
 * Time: 13:49
 */

namespace App\Service;


use App\Entity\ApiReqHis;
use App\Entity\Clients;
use App\Exception\ClientIdLimitException;
use App\Repository\ApiReqHisRepository;
use App\Repository\ClientsRepository;
use App\ServiceInterface\ApiReqHisServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class ApiReqHisService extends BaseService implements ApiReqHisServiceInterface
{

    /**
     * @var ApiReqHisRepository
     */
    protected $repo;

    /**
     * @var ClientsRepository
     */
    protected $clientsRepo;

    public function __construct(ApiReqHisRepository $repository, ClientsRepository $clientsRepository)
    {
        $this->repo = $repository;
        $this->clientsRepo = $clientsRepository;
    }


    /**
     * @param Clients  $clients
     * @param $serviceType
     * @return \by\infrastructure\base\CallResult|void
     * @throws ClientIdLimitException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function check(Clients $clients, $serviceType)
    {
        //TODO: 做缓存 一旦今日超限，则记录，之后就不执行下面代码减少数据库操作
        $map = [
            'client_id' => $clients->getClientId()
        ];
        $ymd = date("Ymd");
        $dayLimit = $clients->getDayLimit();
        $totalLimit = $clients->getTotalLimit();

        // 总次数限制检测
        if ($totalLimit > 0) {
            $totalCnt = $this->repo->sum($map, "cnt");
            if ($totalLimit <= $totalCnt) {
                $message = ["requests exceeded the limit %totalCnt%", ['%totalCnt%' => $totalLimit]];
                throw new ClientIdLimitException($message);
            }
        }

        // 每日限制检测
        if ($dayLimit > 0) {
            $map['ymd'] = $ymd;
            $dayCnt = $this->repo->sum($map, "cnt");
            if ($dayLimit <= $dayCnt) {
                $message = ["requests exceeded the limit today %dayCnt%", ['%dayCnt%' => $dayLimit]];
                throw new ClientIdLimitException($message);
            }
        }

        // 检测通过则记录日志
        $map = [
            'client_id' => $clients->getClientId(),
            'service_type' => $serviceType,
            'ymd' => $ymd
        ];
        $logInfo = $this->repo->findOneBy($map);
        if ($logInfo instanceof ApiReqHis) {
            $logInfo->setCnt($logInfo->getCnt() + 1);
            $this->repo->flush();
        } else {
            $logInfo = new ApiReqHis();
            $logInfo->setYmd($ymd);
            $logInfo->setServiceType($serviceType);
            $logInfo->setClientId($clients->getClientId());
            $logInfo->setCnt(1);
            $this->repo->add($logInfo);
        }
    }
}
