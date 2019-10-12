<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Entity\MessageBox;
use App\Repository\MessageBoxRepository;
use App\ServiceInterface\MessageBoxServiceInterface;
use by\component\message\MessageStatusEnum;
use by\component\message\MessageToUidEnum;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Doctrine\ORM\Query\Expr\Join;
use Dbh\SfCoreBundle\Common\BaseService;


class MessageBoxService extends BaseService implements MessageBoxServiceInterface
{
    /**
     * @var MessageBoxRepository
     */
    protected $repo;

    public function __construct(MessageBoxRepository $repository)
    {
        $this->repo = $repository;
    }

    public function receiveUserMessage($uid, $projectId, $type, PagingParams $pagingParams, $readStatus = false)
    {

        $qb = $this->repo->getEntityManager()->createQueryBuilder();
        $qb
            ->select('m.toUid,m.id,m.dtreeType,m.title,m.summary,m.createTime,m.fromUid,m.sendTime,m.extra')
            ->addSelect("ifnull(mb.msgStatus, 0) as msgStatus")
            ->from('App\Entity\Message', 'm')
            ->leftJoin(
                'App\Entity\MessageBox',
                'mb',
                Join::WITH,
                'mb.msgId = m.id'
            )
            ->andWhere('m.status = 1')
            ->where('m.toUid = :toUid or m.toUid = '. MessageToUidEnum::ALL_USER)
            ->andWhere('m.projectId = :projectId')
            ->andWhere('m.dtreeType = :type')
            ->setParameter('toUid', $uid)
            ->setParameter('type', $type)
            ->setParameter("projectId", $projectId)
            ->orderBy('m.createTime', 'DESC');

        if ($readStatus === MessageStatusEnum::NOT_READ || $readStatus === MessageStatusEnum::HAD_READ) {
            $qb->andWhere("ifnull(mb.msgStatus, 0) = ".$readStatus);
        }

        $list =  $qb->select('ifnull(mb.msgStatus, 0) as msgStatus, m.toUid,m.id,m.dtreeType,m.title,m.summary,m.createTime,m.fromUid,m.sendTime,m.extra')->getQuery()->getResult();
        $count = $qb->select('count(m.id) as cnt')->getQuery()->getResult();
        if (is_array($count) && array_key_exists('cnt', $count[0])) {
            $count = $count[0]['cnt'];
        }
        return CallResultHelper::success(['list' => $list, 'count' => $count]);
    }

    public function receiveAllMessage($uid, $projectId, $type, PagingParams $pagingParams, $readStatus = false)
    {
        $qb = $this->repo->getEntityManager()->createQueryBuilder();
        $qb
            ->from('App\Entity\Message', 'm')
            ->leftJoin(
                'App\Entity\MessageBox',
                'mb',
                Join::WITH,
                'mb.msgId = m.id'
            )
            ->andWhere('m.status = 1')
            ->where('m.toUid = :toUid or m.toUid = '. MessageToUidEnum::ALL_USER)
            ->andWhere('m.projectId = :projectId')
            ->andWhere('m.dtreeType = :type')
            ->setParameter('toUid', $uid)
            ->setParameter('type', $type)
            ->setParameter("projectId", $projectId)
            ->orderBy('m.createTime', 'DESC');

        if ($readStatus === MessageStatusEnum::NOT_READ || $readStatus === MessageStatusEnum::HAD_READ) {
            $qb->andWhere("ifnull(mb.msgStatus, 0) = ".$readStatus);
        }

        $list =  $qb->select('ifnull(mb.msgStatus, 0) as msgStatus, m.toUid,m.id,m.dtreeType,m.title,m.summary,m.createTime,m.fromUid,m.sendTime,m.extra')->getQuery()->getResult();
        $count = $qb->select('count(m.id) as cnt')->getQuery()->getResult();
        if (is_array($count) && array_key_exists('cnt', $count[0])) {
            $count = $count[0]['cnt'];
        }
        return CallResultHelper::success(['list' => $list, 'count' => $count]);
    }

    public function receivePublicMessage($projectId, $type, PagingParams $pagingParams, $readStatus = false): CallResult
    {
        $qb = $this->repo->getEntityManager()->createQueryBuilder();
        $qb
            ->from('App\Entity\Message', 'm')
            ->leftJoin(
                'App\Entity\MessageBox',
                'mb',
                Join::WITH,
                'mb.msgId = m.id'
            )
            ->andWhere('m.status = 1')
            ->where('m.toUid = '. MessageToUidEnum::ALL_USER)
            ->andWhere('m.projectId = :projectId')
            ->andWhere('m.dtreeType = :type')
            ->setParameter('type', $type)
            ->setParameter("projectId", $projectId)
            ->orderBy('m.createTime', 'DESC');

        if ($readStatus === MessageStatusEnum::NOT_READ || $readStatus === MessageStatusEnum::HAD_READ) {
            $qb->andWhere("ifnull(mb.msgStatus, 0) = ".$readStatus);
        }

        $list =  $qb->select('m.content,ifnull(mb.msgStatus, 0) as msgStatus, m.toUid,m.id,m.dtreeType,m.title,m.summary,m.createTime,m.fromUid,m.sendTime,m.extra')->getQuery()->getResult();
        $count = $qb->select('count(m.id) as cnt')->getQuery()->getResult();
        if (is_array($count) && array_key_exists('cnt', $count[0])) {
            $count = $count[0]['cnt'];
        }
        return CallResultHelper::success(['list' => $list, 'count' => $count]);
    }

    /**
     * @param $uid
     * @param $msgId
     * @return MessageBox|mixed|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function read($uid, $msgId)
    {
        $msgBox = $this->info(['uid' => $uid, 'msgId' => $msgId]);

        if ($msgBox instanceof MessageBox) {
            if ($msgBox->getMsgStatus() == MessageStatusEnum::NOT_READ) {
                // 存在且为未读状态
                $msgBox->setMsgStatus(MessageStatusEnum::HAD_READ);
                $this->flush($msgBox);
            }
        } else {
            $msgBox = new MessageBox();
            $msgBox->setMsgStatus(MessageStatusEnum::HAD_READ);
            $msgBox->setUid($uid);
            $msgBox->setMsgId($msgId);
            $this->add($msgBox);
        }

        return $msgBox;
    }
}
