<?php


namespace App\AdminController;


use App\ServiceInterface\WithdrawServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class WithdrawController extends BaseNeedLoginController
{
    protected $withdrawService;

    public function __construct(
        WithdrawServiceInterface $withdrawService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->withdrawService = $withdrawService;
    }

    /**
     * @param PagingParams $pagingParams
     * @param int $auditStatus
     * @return mixed
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams, $auditStatus = AuditStatus::Initial)  {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];
        $map['audit_status'] = intval($auditStatus);
        return $this->withdrawService->queryAndCount($map, $pagingParams, ["createTime" => "desc"]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotLoginException
     */
    public function pass($id) {
        $this->checkLogin();
        return $this->withdrawService->pass($id, $this->getUid(), $this->getLoginUserNick());
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotLoginException
     */
    public function deny($id) {
        $this->checkLogin();
        return $this->withdrawService->deny($id, $this->getUid(), $this->getLoginUserNick());
    }
}
