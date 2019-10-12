<?php


namespace App\AdminController;


use App\Entity\PlatformWallet;
use App\ServiceInterface\PlatformWalletLogServiceInterface;
use App\ServiceInterface\PlatformWalletServiceInterface;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class PlatformController extends BaseNeedLoginController
{
    protected $platformWalletService;
    protected $logService;

    public function __construct(
        PlatformWalletLogServiceInterface $logService,
        PlatformWalletServiceInterface $platformWalletService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->platformWalletService = $platformWalletService;
        $this->logService = $logService;
    }

    /**
     * @throws \by\component\exception\NotLoginException
     */
    public function query() {
        $this->checkLogin();
        return $this->platformWalletService->queryAllBy([]);
    }

    public function history($id, PagingParams $pagingParams) {
        $map = [
            'wallet_id' => $id
        ];
        return $this->logService->queryAndCount($map, $pagingParams, ["id" => "desc"]);
    }
}
