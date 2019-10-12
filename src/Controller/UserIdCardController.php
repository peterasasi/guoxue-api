<?php


namespace App\Controller;


use App\Entity\AuditLog;
use App\Entity\UserAccount;
use App\Entity\UserBankCard;
use App\Entity\UserIdCard;
use App\Exception\NoParamException;
use App\Service\CbPayService;
use App\ServiceInterface\AuditLogServiceInterface;
use App\ServiceInterface\CfPayInterface;

use by\component\exception\NotLoginException;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserBankCardServiceInterface;
use App\ServiceInterface\UserIdCardServiceInterface;
use by\component\helper\ValidateHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

class UserIdCardController extends BaseNeedLoginController
{
    protected $userIdCardService;
    protected $userBankCardService;
    protected $auditLogService;

    public function __construct(
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, AuditLogServiceInterface $auditLogService, UserIdCardServiceInterface $userIdCardService, UserBankCardServiceInterface $userBankCardService, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->userBankCardService = $userBankCardService;
        $this->userIdCardService = $userIdCardService;
        $this->auditLogService = $auditLogService;
    }

    /**
     * 更新
     * @param $id
     * @param $name
     * @param $idNo
     * @param $idFrontImg
     * @param $idBackImg
     * @return CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws NotLoginException
     */
    public function createOrUpdate($name, $idNo, $idFrontImg, $idBackImg)
    {
        $this->checkLogin();
        $userId = $this->getUid();
        $userAccount = $this->userAccountService->info(['id' => $userId]);
        if (!($userAccount instanceof UserAccount)) return 'user is not exists';

        // 已认证 则返回
        if ($userAccount->getProfile()->isIdentityValidate()) {
            return 'user verified';
        }
        // 是否存在记录
        $userIdCard = $this->userIdCardService->info(['uid' => $userId]);
        if (!$userIdCard instanceof UserIdCard) {
            $userIdCard = new UserIdCard();
            $userIdCard->setUid($userId);
        } else {
            // 如果更新则需要重新认证
            $userIdCard->setVerify(0);
        }
        $userIdCard->setCardNo($idNo);
        $userIdCard->setRealName($name);
        $userIdCard->setFrontImg($idFrontImg);
        $userIdCard->setBackImg($idBackImg);
        $birthday = strlen($idNo) == 15 ? ('19' . substr($idNo, 6, 6)) : substr($idNo, 6, 8);
        $userIdCard->setBirthday($birthday);
        $sex = substr($idNo, (strlen($idNo) == 15 ? -2 : -1), 1) % 2 ? '1' : '0';
        $userIdCard->setSex($sex == '1' ? true : false);

        if ($this->userIdCardService->getEntityManager()->contains($userIdCard)) {
            $this->userIdCardService->flush($userIdCard);
        } else {
            $this->userIdCardService->add($userIdCard);
        }
        return CallResultHelper::success($userIdCard->getId());
    }


    /**
     * 先查询是否有记录，记录是否认证失败了
     * @return array|CallResult
     * @throws NotLoginException
     */
    public function info()
    {
        $this->checkLogin();
        $userId = $this->getUid();
        $idCard = $this->userIdCardService->info(['uid' => $userId]);
        if (!($idCard instanceof UserIdCard)) {
            return CallResultHelper::success(['is_first' => 1]);
        }
        $list = $this->auditLogService->queryAllBy(['object_id' => $userId, 'object_type' => AuditLog::IdentityAuth], ["id" => "desc"]);

        return CallResultHelper::success([
            'is_first' => 0,
            'verify' => $idCard->getVerify(),
            'id_front_img' => $idCard->getFrontImg(),
            'id_back_img' => $idCard->getBackImg(),
            'id_no' => $idCard->getCardNo(),
            'name' => $idCard->getRealName(),
            'log_his' => $list //审核日志
        ]);
    }

}
