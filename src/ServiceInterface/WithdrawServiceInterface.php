<?php


namespace App\ServiceInterface;


use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface WithdrawServiceInterface extends BaseServiceInterface
{
    /**
     * 发起提现到银行卡申请
     * @param $uid
     * @param $amount
     * @param $cardNo
     * @param $bankName
     * @param $branchName
     * @param $name
     * @return mixed
     */
    public function apply($uid, $amount, $cardNo, $bankName, $branchName, $name);


    public function pass($id, $auditUid, $auditNick);

    public function deny($id, $auditUid, $auditNick);
}
