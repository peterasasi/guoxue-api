<?php


namespace App\ServiceInterface;


use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface PlatformWalletServiceInterface extends BaseServiceInterface
{
    /**
     * @param $wid
     * @param float $money 单位: 元
     * @param string $note
     * @return mixed
     */
    public function addMoneyTo($wid, $money, $note = '增加金额');
}

