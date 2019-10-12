<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\Message;
use App\Entity\MessageBox;
use App\ServiceInterface\MessageBoxServiceInterface;
use App\ServiceInterface\MessageServiceInterface;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use by\component\message\MessageIsDeliveryEnum;
use by\component\message\MessageStatusEnum;
use by\component\message\MessageToUidEnum;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class MessageController extends BaseNeedLoginController
{
    /**
     * @var MessageServiceInterface
     */
    protected $service;

    /**
     * @var MessageBoxServiceInterface
     */
    protected $msgBoxService;


    public function __construct(UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, MessageBoxServiceInterface $messageBoxService, MessageServiceInterface $messageService, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->service = $messageService;
        $this->msgBoxService = $messageBoxService;
    }

    /**
     *
     * @param $title
     * @param $dtreeType
     * @param $summary
     * @param $content
     * @param $fromUid
     * @param $toUid
     * @param int $sendTime
     * @param string $extra
     * @return Message
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($title, $dtreeType, $summary, $content, $fromUid, $toUid, $sendTime = 0, $extra = '') {
        $msg = new Message();
        $msg->setTitle($title);
        $msg->setStatus(StatusEnum::ENABLE);
        $msg->setContent($content);
        $msg->setDtreeType($dtreeType);
        $msg->setExtra($extra);
        $msg->setFromUid($fromUid);
        $msg->setToUid($toUid);
        $msg->setSummary($summary);
        $msg->setSendTime($sendTime);
        $msg->setProjectId($this->getProjectId());
        $msg->setIsDelivery(MessageIsDeliveryEnum::WAIT);
        $msgBoxEntity = new MessageBox();
        $msgBoxEntity->setUid(0);
        if ($toUid != MessageToUidEnum::ALL_USER) {
            // 点对点
            $msgBoxEntity->setUid($toUid);
            $msgBoxEntity->setMsgStatus(MessageStatusEnum::NOT_READ);
        }
        $this->service->add($msg);
        if ($msgBoxEntity->getUid() === $msg->getToUid()) {
            $msgBoxEntity->setMsgId($msg->getId());
            $this->msgBoxService->add($msgBoxEntity);
        }
        return $msg;
    }


    public function countUserUnreadMessage()
    {
        $cntToMe = $this->service->count(['toUid' => $this->getUid()]);
        $cntAll = $this->service->count(['toUid' => MessageToUidEnum::ALL_USER]);
        $hadRead = $this->msgBoxService->count(['uid' => $this->getUid(), 'msgStatus' => MessageStatusEnum::HAD_READ]);
        return $cntAll + $cntToMe - $hadRead;
    }

    public function queryAllMessage($dtreeType, PagingParams $pagingParams, $msgStatus = '') {
        $uid = $this->getUid();
        return $this->msgBoxService->receiveAllMessage($uid, $this->getProjectId(), $dtreeType, $pagingParams, intval($msgStatus));
    }

    public function queryUserMessage($dtreeType, PagingParams $pagingParams, $msgStatus = '') {
        $uid = $this->getUid();
        return $this->msgBoxService->receiveUserMessage($uid, $this->getProjectId(), $dtreeType, $pagingParams, intval($msgStatus));
    }

    public function queryPublicMessage($dtreeType, PagingParams $pagingParams, $msgStatus = '') {
        return $this->msgBoxService->receivePublicMessage($this->getProjectId(), $dtreeType, $pagingParams, intval($msgStatus));
    }

    public function readBatch($msgIds)
    {
        $uid = $this->getUid();
        $msgIdArr = explode(",", $msgIds);
        foreach ($msgIdArr as $msgId) {
            if (intval($msgId) > 0) {
                $this->read($uid, $msgId);
            }
        }
        return CallResultHelper::success();
    }

    public function read($msgId) {
        $uid = $this->getUid();
        $this->msgBoxService->read($uid, $msgId);
        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function info($id) {
        $uid = $this->getUid();
        $this->msgBoxService->read($uid, $id);
        return $this->service->info(['id' => $id]);
    }
}
