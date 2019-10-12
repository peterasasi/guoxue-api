<?php


namespace App\ServiceInterface;

use App\Entity\UserWallet;
use App\Entity\UserWalletLog;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;
use by\infrastructure\base\CallResult;

interface UserWalletServiceInterface extends BaseServiceInterface
{
    /**
     * 获取用户钱包，如果不存在，则会创建一个
     * @param $uid
     * @return UserWallet|null
     */
    public function safeGetWalletInfo($uid);


    /**
     * 存入电子账户
     * @param integer $walletId 钱包Id
     * @param integer $money 单位:分
     * @param string $logType 存入类型
     * @param string $note 备注
     * @return CallResult
     */
    public function depositCommission($walletId, $money, $note = '', $logType = UserWalletLog::LogTypeDeposit);

    /**
     * 存入电子账户
     * @param integer $walletId 钱包Id
     * @param integer $money 单位:分
     * @param string $logType 存入类型
     * @param string $note 备注
     * @return CallResult
     */
    public function deposit($walletId, $money, $note = '', $logType = UserWalletLog::LogTypeDeposit);


    /**
     * 直接扣除电子账户余额, 没有冻结过程
     * @param integer $walletId 钱包Id
     * @param int $money 提现金额 单位: 分
     * @param string $note
     * @param string $logType 类型 参考CbUserWalletLog
     * @return CallResult
     */
    public function withdraw($walletId, $money, $note, $logType = UserWalletLog::LogTypeWithdraw);


    /**
     * 解冻资金 并回退到账户余额
     * @param integer $walletId 钱包Id
     * @param integer $unfreezeMoney
     * @param string $note
     * @return CallResult
     */
    public function unfreezeToBack($walletId, $unfreezeMoney, $note = '解冻资金');


    /**
     * 解冻资金 并释放该解冻金额，增加提现成功余额
     * @param integer $walletId 钱包Id
     * @param $unfreezeMoney
     * @param string $note
     * @return mixed
     */
    public function unfreezeToSuccess($walletId, $unfreezeMoney, $note = '解冻资金');

    /**
     * 冻结资金
     * @param integer $walletId 钱包Id
     * @param integer $frozenMoney
     * @param string $note
     * @return mixed
     */
    public function freeze($walletId, $frozenMoney, $note = '冻结资金');

}
