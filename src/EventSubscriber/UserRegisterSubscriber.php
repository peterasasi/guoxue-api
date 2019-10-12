<?php


namespace App\EventSubscriber;


use App\Common\GxGlobalConfig;
use App\Entity\ProfitGraph;
use App\Events\UserRegisterEvent;
use App\ServiceInterface\ProfitGraphServiceInterface;
use Doctrine\DBAL\LockMode;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    protected $profitGraphService;
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        ProfitGraphServiceInterface $profitGraphService)
    {
        $this->profitGraphService = $profitGraphService;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            UserRegisterEvent::class => "onRegister"
        ];
    }

    public function onRegister(UserRegisterEvent $event)
    {
        $this->logger->debug("onRegister");
        $uid = $event->getInviteUid();
        if ($uid > 0) {
            $profitGraph = $this->profitGraphService->info(['uid'=> $uid]);
            if ($profitGraph instanceof ProfitGraph) {
                $this->profitGraphService->getEntityManager()->beginTransaction();
                try {
                    $this->profitGraphService->findById($profitGraph->getId(), LockMode::PESSIMISTIC_WRITE);
                    $profitGraph->setInviteCount($profitGraph->getInviteCount() + 1);
                    if ($profitGraph->getActive() === 0 && $profitGraph->getInviteCount() >= GxGlobalConfig::InviteMinUsers) {
                        $profitGraph->setActive(1);
                    }
                    $this->profitGraphService->flush($profitGraph);
                    $this->profitGraphService->getEntityManager()->commit();
                } catch ( \Exception $exception) {
                    $this->profitGraphService->getEntityManager()->rollback();
                    $this->logger->error('[用户增加邀请人数]'.$exception->getMessage());
                }
            }
        }
    }

}
