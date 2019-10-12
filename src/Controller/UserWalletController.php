<?php


namespace App\Controller;


use by\component\audit_log\AuditStatus;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use App\ServiceInterface\UserWalletLogServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class UserWalletController extends BaseNeedLoginController
{
    protected $walletService;
    protected $logService;
    protected $userLogService;

    public function __construct(
        UserLogServiceInterface $userLogService,
        UserWalletLogServiceInterface $logService,
        UserWalletServiceInterface $walletService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->walletService = $walletService;
        $this->logService = $logService;
        $this->userLogService = $userLogService;
    }


    /**
     * 详情查看
     * @return \App\Entity\UserWallet|null
     * @throws \by\component\exception\NotLoginException
     */
    public function info()
    {
        $this->checkLogin();
        $note = '用户查看了钱包信息';
        $this->logUserAction($this->userLogService, $note);
        return $this->walletService->safeGetWalletInfo($this->getUid());
    }

    /**
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function queryLogHistory(PagingParams $pagingParams)
    {
        $this->checkLogin();
        return $this->logService->queryAndCount(['uid' => $this->getUid()], $pagingParams, ["id" => "desc"]);
    }

}
