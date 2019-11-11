<?php


namespace App\Controller;


use App\Entity\ProfitGraph;
use App\ServiceInterface\ProfitGraphServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class ProfitGraphController extends BaseNeedLoginController
{
    protected $profitGraphService;

    public function __construct(
        ProfitGraphServiceInterface $profitGraphService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->profitGraphService = $profitGraphService;
    }

    public function query(PagingParams $pagingParams, $childUid = 0, $username = '')
    {
        $this->checkLogin();
        $map = [
            'parent_uid' => $this->getUid()
        ];
        if (!empty($username)) {
            $map['username'] = ['like', '%' . $username . '%'];
        }
        if ($childUid > 0) {
            $map['parent_uid'] = $childUid;
        }
        return $this->profitGraphService->queryAndCount($map, $pagingParams, ["createTime" => "desc"]);
    }

    public function info()
    {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];
        $ret = $this->profitGraphService->info($map);
        return $ret;
    }


    public function pvip($curLevel, $family) {

        return $this->profitGraphService->getParentVipAndVip9($curLevel, $family);
    }
}
