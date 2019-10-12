<?php


namespace App\Controller;


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
     * @param $amount
     * @param $cardNo
     * @param $bankName
     * @param $branchName
     * @param $name
     * @return mixed
     * @throws NotLoginException
     */
    public function apply($amount, $cardNo, $bankName, $branchName, $name) {
        $this->checkLogin();
        return $this->withdrawService->apply($this->getUid(), $amount, $cardNo, $bankName, $branchName, $name);
    }

    /**
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams)  {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];
        $fields = ["id", "uid", "createTime", "updateTime", "auditNick", "amount", "toWalletInfo", "auditStatus"];
        return $this->withdrawService->queryAndCount($map, $pagingParams, ["createTime" => "desc"], $fields);
    }
}
