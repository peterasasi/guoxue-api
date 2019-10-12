<?php


namespace App\AdminController;


use App\Entity\AuditLog;
use App\Entity\UserBankCard;
use App\Entity\UserIdCard;
use App\Entity\UserProfile;
use App\ServiceInterface\AuditLogServiceInterface;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserBankCardServiceInterface;
use App\ServiceInterface\UserGradeServiceInterface;
use App\ServiceInterface\UserIdCardServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class UserIdCardController extends BaseNeedLoginController
{
    protected $userIdCardService;
    protected $auditLogService;
    protected $userProfileService;
    protected $userLogService;
    protected $logger;
    protected $userGradeService;

    public function __construct(
        UserGradeServiceInterface $userGradeService,
        LoggerInterface $logger,
        UserLogServiceInterface $userLogService, UserProfileServiceInterface $userProfile, UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, AuditLogServiceInterface $auditLogService, UserIdCardServiceInterface $userIdCardService,  KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->logger = $logger;
        $this->userGradeService = $userGradeService;
        $this->userIdCardService = $userIdCardService;
        $this->auditLogService = $auditLogService;
        $this->userProfileService = $userProfile;
        $this->userLogService = $userLogService;
    }

    /**
     * @param $verify
     * @param PagingParams $pagingParams
     * @return CallResult|string
     */
    public function query($verify, PagingParams $pagingParams)
    {
        $fields = ["id", "uid", "realName", "card_no", "front_img", "back_img", "verify"];
        return $this->userIdCardService->queryAndCount(['verify' => $verify], $pagingParams, ["createTime" => 'desc'], $fields);
    }

    /**
     * 审核通过
     * @param $id
     * @return CallResult|string
     * @throws \by\component\exception\NotLoginException
     */
    public function pass($id)
    {
        $this->checkLogin();
        $userIdCard = $this->userIdCardService->info(['id' => $id]);
        if (!($userIdCard instanceof UserIdCard)) {
            return 'user_id invalid';
        }
        if ($userIdCard->getVerify() == 1) {
            return CallResultHelper::success('', 'verified');
        }

        $userProfile = $this->userProfileService->info(['user' => $userIdCard->getUid()]);
        if (!($userProfile instanceof UserProfile)) return 'user is not exists';

        $this->userIdCardService->getEntityManager()->beginTransaction();
        try {
            // 更新身份证状态
            $userIdCard->setVerify(AuditStatus::Passed);
            $this->userIdCardService->flush($userIdCard);

            if (!$userProfile->isIdentityValidate()) {
                // 更新用户认证状态
                $userProfile->setIdentityValidate(true);
                $this->userProfileService->flush($userProfile);
            }
            $content = 'passed';
            $this->auditLogService->log($content, $this->getUid(), $this->getLoginUserNick(), $userIdCard->getUid(), AuditLog::IdentityAuth);
            $this->userIdCardService->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (Exception $exception) {
            $this->userIdCardService->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }


    /**
     * @param $id
     * @param $reason
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function deny($id, $reason = '')
    {
        $this->checkLogin();
        $userIdCard = $this->userIdCardService->info(['id' => $id]);
        if (!($userIdCard instanceof UserIdCard)) {
            return 'id card not exists';
        }

        $userIdCard->setVerify(AuditStatus::Denied);

        $this->auditLogService->log($reason, $this->getUid(), $this->getLoginUserNick(), $userIdCard->getUid(), AuditLog::IdentityAuth);
        $this->userIdCardService->flush($userIdCard);
        return CallResultHelper::success();
    }
}
