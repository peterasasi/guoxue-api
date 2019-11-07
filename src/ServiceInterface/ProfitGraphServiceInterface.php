<?php


namespace App\ServiceInterface;


use App\Common\GxGlobalConfig;
use by\infrastructure\base\CallResult;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface ProfitGraphServiceInterface extends BaseServiceInterface
{
    public function init($username, $uid, $mobile, $inviteUid);

    public function getParentVipAndVip9($curVipLevel, $family);

    public function getParentsUid($curLevel, $toLevel, $family);

    /**
     * @param $orderId
     * @param $uid
     * @param GxGlobalConfig $gxGlobalConfig
     * @return CallResult
     */
    public function upgradeToVip1($orderId, $uid, GxGlobalConfig $gxGlobalConfig);

    /**
     * @param $orderId
     * @param $uid
     * @return CallResult
     */
    public function upgradeToVipN($orderId, $uid);
}
