<?php


namespace App\AdminController;


use App\Entity\GxOrder;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\WithdrawServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class StaticsController extends BaseNeedLoginController
{
    protected $gxOrderService;
    protected $withdrawService;

    public function __construct(
        WithdrawServiceInterface $withdrawService,
        GxOrderServiceInterface $gxOrderService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->gxOrderService = $gxOrderService;
        $this->withdrawService = $withdrawService;
    }

    public function index() {
        $map = [
            'pay_status' => GxOrder::Paid
        ];
        $gxOrderAmount = $this->gxOrderService->sum($map, "amount");

        $map = [
            'audit_status' => AuditStatus::Passed
        ];
        $withdrawAmount = $this->withdrawService->sum($map, "amount");

        return CallResultHelper::success([
            'gx_order_amount' => $gxOrderAmount,
            'withdraw_amount' => StringHelper::numberFormat($withdrawAmount / 100)
        ]);
    }
}
