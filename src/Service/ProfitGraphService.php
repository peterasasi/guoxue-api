<?php


namespace App\Service;


use App\Common\GxGlobalConfig;
use App\Entity\GxOrder;
use App\Entity\PlatformWallet;
use App\Entity\ProfitGraph;
use App\Entity\UserWallet;
use App\Repository\ProfitGraphRepository;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\PlatformWalletServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Doctrine\DBAL\LockMode;
use Exception;

class ProfitGraphService extends BaseService implements ProfitGraphServiceInterface
{
    protected $platformWalletService;
    protected $gxOrderService;
    protected $userWalletService;
    protected $maxIncome;

    const MaxVip = 10;
    // 顶级 利润 元
    const VipMaxUpgradeProfit = 200;
    // 上级 利润 元
    const VipParentUpgradeProfit = 200;

    public function __construct(
        UserWalletServiceInterface $userWalletService,
        GxOrderServiceInterface $gxOrderService, PlatformWalletServiceInterface $platformWalletService, ProfitGraphRepository $repository)
    {
        $this->repo = $repository;
        $this->platformWalletService = $platformWalletService;
        $this->gxOrderService = $gxOrderService;
        $this->userWalletService = $userWalletService;
        $this->maxIncome = 5900000;
    }

    public function init($username, $uid, $mobile, $inviteUid)
    {
        if ($uid === $inviteUid) $inviteUid = 0;
        $entity = $this->info(['uid' => $uid]);
        if ($entity instanceof ProfitGraph) {
            if (empty($entity->getMobile())) {
                $entity->setMobile($mobile);
                $entity->setUsername($username);
                $this->repo->flush($entity);
            }
            return $entity;
        }
        $family = "";
        if ($inviteUid > 0) {
            $inviteProfitGraph = $this->info(['uid' => $inviteUid]);
            if ($inviteProfitGraph instanceof ProfitGraph) {
                $family = $inviteProfitGraph->getFamily() . $inviteUid . ",";
            }
        }
        $entity = new ProfitGraph();
        $entity->setMobile($mobile);
        $entity->setUid($uid);
        $entity->setUsername($username);
        $entity->setParentUid($inviteUid);
        $entity->setFamily($family);
        $this->repo->add($entity);
        return $entity;
    }


    public function getParentVipAndVip9($curVipLevel, $family)
    {
        if (empty($family)) return [0, 0];
        $family = explode(",", rtrim($family, ','));
        $fields = ["uid", "vip_level", "active", "total_income"];
        $pgList = $this->queryAllBy(['uid' => ['in', $family]], ['id' => 'desc'], $fields);
        $vMax = 0;
        $parentVipUid = 0;
        $vMaxIncome = 0;
        $parentIncome = 0;

        foreach ($pgList as $vo) {
            if ($vo['active'] === 1) {
                if ($vMax === 0 && $vo['vip_level'] == self::MaxVip) {
                    $vMax = $vo['uid'];
                    $vMaxIncome = $vo['total_income'];
                }
                if ($parentVipUid === 0 && $vo['vip_level'] > $curVipLevel) {
                    $parentVipUid = $vo['uid'];
                    $parentIncome = $vo['total_income'];
                }
            }
        }
        if ($vMaxIncome >= $this->maxIncome) {
            // 如果超过限制的收益金额
            $vMax = 0;
        }
        if ($parentIncome >= $this->maxIncome) {
            // 如果超过限制的收益金额
            $parentVipUid = 0;
        }

        return [intval($parentVipUid), intval($vMax)];
    }

    public function getParentsUid($curLevel, $toLevel, $family) {
        // $curLevel + 1,  $toLevel 这个区间的vip
        if (empty($family)) return [];
        $toLevel = $toLevel + 1 > 10 ? 10 : $toLevel + 1;
        $family = explode(",", rtrim($family, ','));
        $fields = ["uid", "vip_level", "active", "total_income"];
        $pgList = $this->queryAllBy(['uid' => ['in', $family]], ['uid' => 'asc'], $fields);
        $parentsUid = [];

        // 跳级给
        for ($i = $curLevel + 2; $i <= $toLevel; $i++) {
            $parentsUid[$i - 1 - $curLevel] = 0;
            foreach ($pgList as $vo) {
                if ($vo['active'] === 1) {
                    if ($parentsUid[$i - 1 - $curLevel] === 0 && $vo['vip_level'] == $i) {
                        if ($vo['total_income'] < $this->maxIncome) {
                            // 如果小于限制的收益金额
                            $parentsUid[$i - 1 - $curLevel] = $vo['uid'];
                        }
                    }
                }
            }
        }
        return $parentsUid;
    }

    public function upgradeToVip1($orderId, $uid, GxGlobalConfig $gxGlobalConfig)
    {

        $profitGraph = $this->info(['uid' => $uid]);
        if (!$profitGraph instanceof ProfitGraph) return CallResultHelper::fail('用户信息缺失利润图');
        if (intval($profitGraph->getVipLevel()) !== 0) return CallResultHelper::fail('该用户不是vip0,无法升级到VIP1');

        // 平台总利润
        $total = $gxGlobalConfig->getPlatformFixedProfit();
        $payFeeWallet = $this->platformWalletService->info(['typeNo' => PlatformWallet::Pay1Fee]);
        if (!$payFeeWallet instanceof PlatformWallet) {
            return CallResultHelper::fail('缺少手续费钱包');
        }
        $balanceWallet = $this->platformWalletService->info(['typeNo' => PlatformWallet::Balance]);
        if (!$balanceWallet instanceof PlatformWallet) {
            return CallResultHelper::fail('缺少余额钱包');
        }
        $otherWallets = $this->platformWalletService->queryAllBy(['profit_ratio' => ['gt', 0]]);

        // 获取上级vipUid 和 vip9的Uid
        list($parentVipUid, $vMaxUid) = $this->getParentVipAndVip9($profitGraph->getVipLevel(), $profitGraph->getFamily());
        $this->platformWalletService->getEntityManager()->beginTransaction();
        try {
            $gxOrder = $this->gxOrderService->findById($orderId, LockMode::PESSIMISTIC_WRITE);
            if (!$gxOrder instanceof GxOrder) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单'.$orderId.'id无效');
            }
            if ($gxOrder->getPayStatus() !== 1) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单'.$orderId.'未支付成功');
            }
            if ($gxOrder->getProcessed() !== 0) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单'.$gxOrder->getId().'已处理');
            }

            // 支付通道手续费
            $payFee = $gxOrder->getFee();
            // 扣除手续费
            $total -= $payFee;
            $note = '升级VIP1订单' . $gxOrder->getId() . '增加手续费' . $payFee . '元';
            $this->platformWalletService->addMoneyTo($payFeeWallet->getId(), $payFee, $note);
            $balanceProfitRatio = 100;
            foreach ($otherWallets as $wlt) {
                if (is_array($wlt)) {
                    $balanceProfitRatio -= $wlt['profit_ratio'];
                    $ratio = StringHelper::numberFormat($wlt['profit_ratio'] / 100, 2);
                    $money = StringHelper::numberFormat($total * $ratio, 3);
                    $note = '升级VIP1订单' . $gxOrder->getId() . '增加' . $wlt['type_no'] . '余额' . $money . '元';
                    $this->platformWalletService->addMoneyTo($wlt['id'], $money, $note);
                }
            }
            if ($balanceProfitRatio < 0) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('处理订单'.$gxOrder->getId().'缺少余额钱包');
            } else {
                // 余额钱包
                $money = $total * StringHelper::numberFormat($balanceProfitRatio / 100, 2);
                $note = '升级VIP1订单' . $gxOrder->getId() . '增加平台总余额' . $money . '元';
                $this->platformWalletService->addMoneyTo($balanceWallet->getId(), $money, $note);
            }

            //  获取用户直推 给予200元
            $money = self::VipParentUpgradeProfit;
            if ($parentVipUid <= 0) {
                // 给予平台 余额钱包
                $note = '[无上级截留]升级VIP1订单' . $gxOrder->getId() . '增加平台总余额' . $money . '元';
                $this->platformWalletService->addMoneyTo($balanceWallet->getId(), $money, $note);
            } else {
                $userWallet = $this->userWalletService->info(['uid' => $parentVipUid]);
                if (!$userWallet instanceof UserWallet) {
                    $this->getEntityManager()->rollback();
                    return CallResultHelper::fail('订单'.$orderId.'上级VipUid无效');
                }

                $note = '[佣金]下级用户'.$gxOrder->getUid().'升级VIP1增加佣金' . $money . '元';
                $this->userWalletService->depositCommission($userWallet->getId(), $money * 100, $note);
                // 增加利润的收益
                $this->addIncome($userWallet->getUid(), $money);
            }
            //  获取已激活的vipMax 给予200 元
            $money = self::VipMaxUpgradeProfit;
            if ($vMaxUid <= 0) {
                // 给予平台 余额钱包
                $note = '[VMax截留]升级VIP1订单' . $gxOrder->getId() . '增加平台总余额' . $money . '元';
                $this->platformWalletService->addMoneyTo($balanceWallet->getId(), $money, $note);
            } else {
                $userWallet = $this->userWalletService->info(['uid' => $vMaxUid]);
                if (!$userWallet instanceof UserWallet) {
                    $this->getEntityManager()->rollback();
                    return CallResultHelper::fail('订单'.$orderId.'VipMax无效');
                }
                $note = '[VIPMax佣金]下级用户'.$gxOrder->getUid().'升级VIP1增加佣金' . $money . '元';
                $this->userWalletService->depositCommission($userWallet->getId(), $money * 100, $note);
                // 增加利润的收益
                $this->addIncome($userWallet->getUid(), $money);
            }

            // 2. 更新用户等级到vip1
            $profitGraph->setVipLevel(1);
            // 设置订单已处理
            $gxOrder->setProcessed(1);

            $this->flush($profitGraph);
            $this->gxOrderService->flush($gxOrder);
            $this->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (Exception $exception) {
            $this->getEntityManager()->rollback();
            return CallResultHelper::fail('[GXPAY]处理订单' . $orderId . '发生异常' . $exception->getMessage());
        }
    }

    public function upgradeToVipN($orderId, $uid) {

        $profitGraph = $this->info(['uid' => $uid]);
        if (!$profitGraph instanceof ProfitGraph) return CallResultHelper::fail('用户信息缺失利润图');
        if (intval($profitGraph->getVipLevel()) === 0) return CallResultHelper::fail('该用户是vip0,必须先升级到VIP1');

        $balanceWallet = $this->platformWalletService->info(['typeNo' => PlatformWallet::Balance]);
        if (!$balanceWallet instanceof PlatformWallet) {
            return CallResultHelper::fail('缺少余额钱包');
        }

        $payFeeWallet = $this->platformWalletService->info(['typeNo' => PlatformWallet::Pay1Fee]);
        if (!$payFeeWallet instanceof PlatformWallet) {
            return CallResultHelper::fail('缺少手续费钱包');
        }

        // 开启事务处理
        $this->gxOrderService->getEntityManager()->beginTransaction();
        try {
            $gxOrder = $this->gxOrderService->findById($orderId, LockMode::PESSIMISTIC_WRITE);
            if (!$gxOrder instanceof GxOrder) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单' . $orderId . 'id无效');
            }
            if ($gxOrder->getPayStatus() !== 1) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单' . $orderId . '未支付成功');
            }
            if ($gxOrder->getProcessed() !== 0) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('订单' . $gxOrder->getId() . '已处理');
            }
            $vipLevel = $gxOrder->getVipItemId();
            if (intval($profitGraph->getVipLevel()) >= $vipLevel) {
                $this->getEntityManager()->rollback();
                return CallResultHelper::fail('当前用户等级大于升级的等级,订单' . $gxOrder->getId() . '处理失败');
            }
            // 扣除补充的 和 手续费
            $amount = $gxOrder->getAmount() - $gxOrder->getExtraAmount();
            $fee = $gxOrder->getFee();

            $note = '订单' . $gxOrder->getId() . ':VIP' . $profitGraph->getVipLevel() . '升级到VIP' . $vipLevel . '费用的手续费' . $fee . '元';
            $this->platformWalletService->addMoneyTo($payFeeWallet->getId(), $fee, $note);

            // 给予升级费用
            $parentsUid = $this->getParentsUid($profitGraph->getVipLevel(), $vipLevel, $profitGraph->getFamily());

            // 扣除手续费之后
            $amount = StringHelper::numberFormat(($amount - $fee) / ($vipLevel - $profitGraph->getVipLevel()), 2);

            foreach ($parentsUid as $parentVipUid) {
                if ($parentVipUid <= 0) {
                    // 给予平台 余额钱包
                    $note = '[无上级截留]升级VIP' . $vipLevel . '订单' . $gxOrder->getId() . '增加平台总余额' . $amount . '元';
                    $this->platformWalletService->addMoneyTo($balanceWallet->getId(), $amount, $note);
                } else {
                    $userWallet = $this->userWalletService->info(['uid' => $parentVipUid]);
                    if (!$userWallet instanceof UserWallet) {
                        $this->getEntityManager()->rollback();
                        return CallResultHelper::fail('订单' . $orderId . '上级VipUid无效');
                    }
                    $note = '[VIP升级佣金]下级用户' . $gxOrder->getUid() . '升级VIP' . $vipLevel . '增加佣金' . $amount . '元';
                    $this->userWalletService->depositCommission($userWallet->getId(), $amount * 100, $note);
                    $this->addIncome($userWallet->getUid(), $amount);
                }
            }
            // 2. 更新用户等级到vip-n
            $profitGraph->setVipLevel($vipLevel);
            // 设置订单已处理
            $gxOrder->setProcessed(1);
            $this->flush($profitGraph);
            $this->gxOrderService->flush($gxOrder);
            $this->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (Exception $exception) {
            $this->getEntityManager()->rollback();
            return CallResultHelper::fail('[GXPAY]处理订单VIP-N' . $orderId . '发生异常' . $exception->getMessage());
        }
    }

    protected function addIncome($uid, $income) {
        $pg = $this->info(['uid' => $uid]);
        if ($pg instanceof ProfitGraph) {
            $pg = $this->findById($pg->getId(), LockMode::PESSIMISTIC_WRITE);
            $pg->setTotalIncome($pg->getTotalIncome() + $income);
            $this->flush($pg);
        }
    }
}
