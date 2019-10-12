<?php


namespace App\Service;


use App\Entity\UserWallet;
use App\Entity\UserWalletLog;
use App\Repository\UserWalletLogRepository;
use App\Repository\UserWalletRepository;
use App\ServiceInterface\UserWalletServiceInterface;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Doctrine\DBAL\LockMode;
use Dbh\SfCoreBundle\Common\BaseService;


class UserWalletService extends BaseService implements UserWalletServiceInterface
{
    protected $walletLogRepo;

    public function __construct(UserWalletLogRepository $logRepository, UserWalletRepository $repository)
    {
        $this->walletLogRepo = $logRepository;
        $this->repo = $repository;
    }

    /**
     * 获取钱包信息，如果没有 则新增一条记录
     * 这里会有并发问题 ，并发量大时会重复插入数据, 所以钱包表uid加唯一索引
     * @param $uid
     * @return UserWallet|mixed|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function safeGetWalletInfo($uid)
    {
        $wallet = $this->repo->findOneBy(['uid' => $uid]);
        if ($wallet instanceof UserWallet) {
            return $wallet;
        }

        $wallet = new UserWallet();
        $wallet->setUid($uid);
        $wallet->setBalance(0);
        $wallet->setFrozen(0);
        $wallet->setWithdrawTotal(0);
        return $this->repo->add($wallet);
    }


    public function depositCommission($walletId, $money, $note = '', $logType = UserWalletLog::LogTypeDeposit)
    {
        $money = abs($money);
        // 改变余额
        // 记录日志
        if (empty($note)) {
            $note = '增加了' . StringHelper::numberFormat($money / 100) . '元';
        }
        $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
        $wallet->setBalance($wallet->getBalance() + $money);
        $log = new UserWalletLog();
        $log->setUid($wallet->getUid());
        $log->setContent($note);
        $log->setChangeMoney($money);
        $log->setLogType($logType);
        $this->walletLogRepo->add($log);
        $this->repo->flush($wallet);
        return CallResultHelper::success();
    }


    public function deposit($walletId, $money, $note = '', $logType = UserWalletLog::LogTypeDeposit)
    {
        $this->repo->getEntityManager()->beginTransaction();
        $money = abs($money);

        try {
            // 改变余额
            // 记录日志
            if (empty($note)) {
                $note = '增加了' . StringHelper::numberFormat($money / 100) . '元';
            }
            $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
            $wallet->setBalance($wallet->getBalance() + $money);
            $log = new UserWalletLog();
            $log->setUid($wallet->getUid());
            $log->setContent($note);
            $log->setChangeMoney($money);
            $log->setLogType($logType);
            $this->walletLogRepo->add($log);
            $this->repo->flush($wallet);
            $this->repo->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (\Exception $exception) {
            $this->repo->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    public function withdraw($walletId, $money, $note, $logType = UserWalletLog::LogTypeWithdraw)
    {

        // 转成正数
        $money = abs($money);

        $this->repo->getEntityManager()->beginTransaction();
        try {
            // 改变余额
            // 记录日志
            if (empty($note)) {
                $note = '支出' . abs($money / 100) . '元';
            }

            $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
            if ($wallet->getBalance() - $money < 0) {
                $this->repo->getEntityManager()->rollback();
                return CallResultHelper::fail('用户' . $wallet->getUid() . '可用资金' . $wallet->getBalance() . '不足以扣除' . $money);
            }
            // 减少可用余额
            $wallet->setBalance($wallet->getBalance() - $money);

            $wallet->setWithdrawTotal($wallet->getWithdrawTotal() + $money);

            $log = new UserWalletLog();
            $log->setUid($wallet->getUid());
            $log->setContent($note);
            $log->setChangeMoney(0 - $money);
            $log->setLogType($logType);

            $this->walletLogRepo->add($log);
            $this->repo->flush($wallet);
            $this->repo->getEntityManager()->commit();

            return CallResultHelper::success();
        } catch (\Exception $exception) {
            $this->repo->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    public function unfreezeToBack($walletId, $unfreezeMoney, $note = '解冻资金')
    {

        $this->repo->getEntityManager()->beginTransaction();
        $unfreezeMoney = abs($unfreezeMoney);
        try {
            // 改变余额
            // 记录日志
            if (empty($note)) {
                $note = '解冻' . StringHelper::numberFormat($unfreezeMoney / 100) . '元';
            }

            $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
            if ($wallet->getFrozen() < $unfreezeMoney) {
                $this->repo->getEntityManager()->rollback();
                return CallResultHelper::fail('用户' . $wallet->getUid() . '冻结资金不足以扣除' . StringHelper::numberFormat($unfreezeMoney / 100) . '元');
            }

            // 增加余额
            $wallet->setBalance($wallet->getBalance() + $unfreezeMoney);
            // 扣除冻结资金
            $wallet->setFrozen($wallet->getFrozen() - $unfreezeMoney);

            $log = new UserWalletLog();
            $log->setUid($wallet->getUid());
            $log->setContent($note);
            $log->setChangeMoney($unfreezeMoney);
            $log->setLogType(UserWalletLog::LogTypeUnfreezeBack);

            $this->walletLogRepo->add($log);
            $this->repo->flush($wallet);

            $this->repo->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (\Exception $exception) {
            $this->repo->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    public function unfreezeToSuccess($walletId, $unfreezeMoney, $note = '解冻资金')
    {

        $this->repo->getEntityManager()->beginTransaction();
        $unfreezeMoney = abs($unfreezeMoney);
        try {
            // 改变余额
            // 记录日志
            if (empty($note)) {
                $note = '解冻' . StringHelper::numberFormat($unfreezeMoney / 100) . '元';
            }

            $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
            if ($wallet->getFrozen() < $unfreezeMoney) {
                $this->repo->getEntityManager()->rollback();
                return CallResultHelper::fail('用户' . $wallet->getUid() . '冻结资金不足以扣除' . StringHelper::numberFormat($unfreezeMoney / 100) . '元');
            }

            // 增加提现成功金额
            $wallet->setWithdrawTotal($wallet->getWithdrawTotal() + $unfreezeMoney);
            $wallet->setFrozen($wallet->getFrozen() - $unfreezeMoney);

            $log = new UserWalletLog();
            $log->setUid($wallet->getUid());
            $log->setContent($note);
            $log->setChangeMoney(0 - $unfreezeMoney);
            $log->setLogType(UserWalletLog::LogTypeUnfreeze);

            $this->walletLogRepo->add($log);
            $this->repo->flush($wallet);

            $this->repo->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (\Exception $exception) {
            $this->repo->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    public function freeze($walletId, $frozenMoney, $note = '冻结资金')
    {
        if ($frozenMoney == 0) {
            return CallResultHelper::fail('金额不能为0');
        }
        $this->repo->getEntityManager()->beginTransaction();
        $frozenMoney = abs($frozenMoney);
        try {
            // 改变余额
            // 记录日志
            if (empty($note)) {
                $note = '冻结' . StringHelper::numberFormat($frozenMoney / 100) . '元';
            }

            $wallet = $this->repo->find($walletId, LockMode::PESSIMISTIC_WRITE);
            if ($wallet->getBalance() - $frozenMoney < 0) {
                $this->repo->getEntityManager()->rollback();
                return CallResultHelper::fail('用户' . $wallet->getUid() . '资金不足以冻结' . StringHelper::numberFormat($frozenMoney / 100) . '元');
            }

            // 扣除余额
            $wallet->setBalance($wallet->getBalance() - $frozenMoney);
            // 增加冻结金额
            $wallet->setFrozen($wallet->getFrozen() + $frozenMoney);

            $log = new UserWalletLog();
            $log->setUid($wallet->getUid());
            $log->setContent($note);
            $log->setChangeMoney(0 - $frozenMoney);
            $log->setLogType(UserWalletLog::LogTypeFreeze);

            $this->walletLogRepo->add($log);
            $this->repo->flush($wallet);

            $this->repo->getEntityManager()->commit();
            return CallResultHelper::success();
        } catch (\Exception $exception) {
            $this->repo->getEntityManager()->rollback();
            return CallResultHelper::fail($exception->getMessage());
        }
    }


}
