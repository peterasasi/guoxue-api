<?php


namespace App\AdminController;


use App\Entity\UserGrade;
use App\ServiceInterface\AuditLogServiceInterface;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserBankCardServiceInterface;
use App\ServiceInterface\UserGradeServiceInterface;
use App\ServiceInterface\UserIdCardServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class UserGradeController extends BaseNeedLoginController
{
    protected $userIdCardService;
    protected $userBankCardService;
    protected $auditLogService;
    protected $userProfileService;
    protected $userLogService;
    protected $logger;
    protected $userGradeService;

    public function __construct(
        UserGradeServiceInterface $userGradeService,
        LoggerInterface $logger,
        UserLogServiceInterface $userLogService, UserProfileServiceInterface $userProfile, UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, AuditLogServiceInterface $auditLogService, UserIdCardServiceInterface $userIdCardService, UserBankCardServiceInterface $userBankCardService, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->logger = $logger;
        $this->userGradeService = $userGradeService;
        $this->userBankCardService = $userBankCardService;
        $this->userIdCardService = $userIdCardService;
        $this->auditLogService = $auditLogService;
        $this->userProfileService = $userProfile;
        $this->userLogService = $userLogService;
    }

    /**
     * 同时更新用户3个渠道的手续费
     * @param $userId
     * @param $gradeId
     * @return CallResult|string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function setGrade($userId, $gradeId)
    {
        $this->checkLogin();
        if ($gradeId != UserGrade::Normal && $gradeId != UserGrade::VIP1) return 'invalid grade_id';
        $this->userGradeService->updateOne(['uid' => $userId], ['grade_id' => $gradeId]);

        return CallResultHelper::success();
    }

}
