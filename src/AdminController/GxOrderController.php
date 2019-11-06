<?php


namespace App\AdminController;


use App\Common\GxGlobalConfig;
use App\Entity\GxOrder;
use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Helper\CodeGenerator;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class GxOrderController extends BaseNeedLoginController
{
    protected $gxOrderService;
    protected $gxConfig;
    protected $profitGraphService;

    public function __construct(
        ProfitGraphServiceInterface $profitGraphService,
        GxGlobalConfig $gxGlobalConfig,
        GxOrderServiceInterface $gxOrderService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->gxOrderService = $gxOrderService;
        $this->gxConfig = $gxGlobalConfig;
        $this->profitGraphService = $profitGraphService;
    }

    /**
     * 查询订单
     * @param PagingParams $pagingParams
     * @param string $mobile
     * @param string $orderNo
     * @return CallResult|string
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams, $mobile = '', $orderNo = '') {
        $this->checkLogin();
        $map = [];
        if (!empty($orderNo)) {
            $map['order_no'] = ['like', '%'.$orderNo.'%'];
        }
        if (!empty($mobile)) {
            $ua = $this->userAccountService->info(['mobile' => $mobile]);
            if ($ua instanceof UserAccount) {
                $map['uid'] = $ua->getId();
            }
        }

        return $this->gxOrderService->queryAndCount($map, $pagingParams, ["id" => "desc"]);
    }
}
