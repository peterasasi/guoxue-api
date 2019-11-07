<?php


namespace App\AdminController;


use App\Entity\ProfitGraph;
use App\ServiceInterface\ProfitGraphServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class ProfitGraphController extends BaseNeedLoginController
{
    protected $profitGraphService;
    protected $userLogService;

    public function __construct(
        UserLogServiceInterface $userLogService,
        ProfitGraphServiceInterface $profitGraphService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->profitGraphService = $profitGraphService;
        $this->userLogService = $userLogService;
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

    /**
     * @param $userId
     * @param $vipLevel
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function modifyLevel($userId, $vipLevel) {
        $this->checkLogin();
        if (!(intval($vipLevel) >= 0 && intval($vipLevel) < 11)) {
            return CallResultHelper::fail('级别调整限定为0-10');
        }
        $map = [
            'uid' => $userId
        ];
        $pg = $this->profitGraphService->info($map);
        if ($pg instanceof ProfitGraph) {
            $note = '用户' . $this->getUid() . '更改用户' . $userId . '等级'.$pg->getVipLevel().'为' . $vipLevel;
            $pg->setVipLevel(intval($vipLevel));
            $this->profitGraphService->flush($pg);
            $this->logUserAction($this->userLogService, $note);
        }
        return CallResultHelper::success();
    }
}
