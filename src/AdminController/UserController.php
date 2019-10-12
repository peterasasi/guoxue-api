<?php


namespace App\AdminController;


use App\Dto\UserInfoDto;
use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Entity\UserWallet;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class UserController extends BaseNeedLoginController
{
    protected $profitGraphService;
    protected $userWalletService;

    public function __construct(
        ProfitGraphServiceInterface $profitGraphService,
        UserWalletServiceInterface $userWalletService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->profitGraphService = $profitGraphService;
        $this->userWalletService = $userWalletService;
    }

    /**
     * @param $userId
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     */
    public function info($userId)
    {
        $this->checkLogin();
        $userAccount = $this->userAccountService->findById($userId);

        $dto = new UserInfoDto();
        if ($userAccount instanceof UserAccount) {
            $dto->setUserAccount($userAccount);
            $pg = $this->profitGraphService->info(['uid' => $userAccount->getId()]);
            if ($pg instanceof ProfitGraph) {
                $dto->setProfitGraph($pg);
            }
            $userWallet = $this->userWalletService->info(['uid' => $userAccount->getId()]);
            if ($userWallet instanceof UserWallet) {
                $dto->setWallet($userWallet);
            }
            return CallResultHelper::success($dto);
        }

        return CallResultHelper::fail();
    }
}
