<?php


namespace App\Controller;


use App\Common\GxGlobalConfig;
use App\Entity\GxOrder;
use App\Entity\ProfitGraph;
use App\Helper\CodeGenerator;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\ByEnv;
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
     * 升级到VIP
     * @param $level
     * @param string $jumpUrl
     * @return CallResult|string
     * @throws NotLoginException
     */
    public function upgradeToVip($level, $jumpUrl = '') {
        $this->checkLogin();
        $level = intval($level);
        if ($level < 1 || $level > 9) {
            return '升级的VIP等级无效';
        }
        $profitGraph = $this->profitGraphService->info(['uid' => $this->getUid()]);
        if ($profitGraph instanceof ProfitGraph) {
            $userLevel = intval($profitGraph->getVipLevel());
            if ($userLevel >= $level) {
                return '无法升级，您是VIP'.$userLevel;
            }
        } else {
            return '该用户无法升级';
        }

        // 大于VIP1 时 必须邀请指定人数才能进行升级
        if ($level > 1 && $profitGraph->getActive() !== 1) {
//            return '您必须邀请指定人数才能进行升级';
        }

        $this->gxConfig->init($this->getProjectId());
        $entity = new GxOrder();
        $remark = '从VIP'.$userLevel.'升级到Vip'.$level;
        if ($level > 1) {
            if ($userLevel === 0) {
                return  '必须成为VIP1后才能升级到其它等级';
            }
            $amount = ($level - $userLevel) * $this->gxConfig->getVipUpgrade();
        } else {
            $amount = $this->gxConfig->getVip1();
        }

        $fee = StringHelper::numberFormat($amount * $this->gxConfig->getPayFee(), 4);
        $entity->setProjectId($this->getProjectId());
        $entity->setUid($this->getUid());
        $entity->setOrderNo(CodeGenerator::orderCode($this->getUid(), StringHelper::randNumbers(4)));
        $entity->setRemark($remark);
        $entity->setAmount($amount);
        $entity->setArrivalAmount(0);
        $entity->setFee($fee);
        $entity->setShowJumpUrl($jumpUrl);
        $entity->setVipItemId($level);
        $this->gxOrderService->add($entity);
        $payUrl = $this->request->getSchemeAndHttpHost() . $this->generateUrl('pay1_start', ['orderNo' => $entity->getOrderNo()]);
        $payUrl = base64_encode($payUrl);
        return CallResultHelper::success($payUrl);
    }

    /**
     * @param PagingParams $pagingParams
     * @return mixed
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams) {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];
        return $this->gxOrderService->queryAndCount($map, $pagingParams, ["id" => "desc"]);
    }
}
