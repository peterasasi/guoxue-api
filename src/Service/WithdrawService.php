<?php


namespace App\Service;


use App\Entity\UserWallet;
use App\Entity\Withdraw;
use App\Repository\WithdrawRepository;
use App\ServiceInterface\WithdrawServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;

class WithdrawService extends BaseService implements WithdrawServiceInterface
{
    protected $userWalletService;

    public function __construct(UserWalletService $userWalletService, WithdrawRepository $repository)
    {
        $this->repo = $repository;
        $this->userWalletService = $userWalletService;
    }

    public function apply($uid, $amount, $cardNo, $bankName, $branchName, $name) {

        // 检测钱包是否有足够的可提现余额
        $wallet = $this->userWalletService->info(['uid' => $uid]);

        if (!$wallet instanceof UserWallet) return CallResultHelper::fail('uid not has wallet');
        if ($amount <= 0) {
            return CallResultHelper::fail('金额错误');
        }

        $note = '发起提现冻结'.StringHelper::numberFormat($amount / 100, 2).'元';

        $ret = $this->userWalletService->freeze($wallet->getId(), $amount,  $note);

        if ($ret->isFail()) return $ret;

        $toWalletInfo = [
            'card_no' => $cardNo,
            'bank_name' => $bankName,
            'name' => $name,
            'branch_name' => $branchName
        ];
        $entity = new Withdraw();
        $entity->setUid($uid);
        $entity->setAuditUid(0);
        $entity->setAuditNick('');
        $entity->setAmount($amount);
        $entity->setAuditStatus(AuditStatus::Initial);
        $entity->setToWalletInfo(json_encode($toWalletInfo));
        $this->repo->add($entity);
        return CallResultHelper::success($entity->getId());
    }

    /**
     * 审核通过
     * @param $id
     * @param $auditUid
     * @param $auditNick
     * @return \by\infrastructure\base\CallResult|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function pass($id, $auditUid, $auditNick) {
        $entity = $this->repo->find($id);
        if (!$entity instanceof Withdraw) {
            return CallResultHelper::fail('invalid id');
        }
        if ($entity->getAuditStatus() !== AuditStatus::Initial) {
            return CallResultHelper::fail('该记录已审核过');
        }
        // 检测钱包是否有足够的可提现余额
        $wallet = $this->userWalletService->info(['uid' => $entity->getUid()]);
        if (!$wallet instanceof UserWallet) return CallResultHelper::fail('uid not has wallet');

        $entity->setAuditNick($auditNick);
        $entity->setAuditUid($auditUid);
        $entity->setAuditStatus(AuditStatus::Passed);
        $this->repo->flush($entity);

        $note = "用户[".$auditUid."]通过提现申请[".$id."]释放冻结资金".StringHelper::numberFormat($entity->getAmount() / 100, 2).'元';
        $ret = $this->userWalletService->unfreezeToSuccess($wallet->getId(), $entity->getAmount(), $note);
        if (!$ret->isFail()) return $ret;
        return CallResultHelper::success();
    }

    /**
     * 审核失败
     * @param $id
     * @param $auditUid
     * @param $auditNick
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deny($id, $auditUid, $auditNick) {
        $entity = $this->repo->find($id);
        if (!$entity instanceof Withdraw) {
            return CallResultHelper::fail('invalid id');
        }
        if ($entity->getAuditStatus() !== AuditStatus::Initial) {
            return CallResultHelper::fail('该记录已审核过');
        }
        // 检测钱包是否有足够的可提现余额
        $wallet = $this->userWalletService->info(['uid' => $entity->getUid()]);
        if (!$wallet instanceof UserWallet) return CallResultHelper::fail('uid not has wallet');
        $entity->setAuditNick($auditNick);
        $entity->setAuditUid($auditUid);
        $entity->setAuditStatus(AuditStatus::Denied);
        $this->repo->flush($entity);

        $note = "用户[".$auditUid."]拒绝了提现申请[".$id."]退回资金".StringHelper::numberFormat($entity->getAmount() / 100, 2).'元';
        $ret = $this->userWalletService->unfreezeToBack($wallet->getId(), $entity->getAmount(), $note);
        if (!$ret->isFail()) return $ret;
        return CallResultHelper::success();
    }
}
