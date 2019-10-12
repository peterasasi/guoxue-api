<?php


namespace App\ServiceInterface;


use App\Entity\PayOrder;
use by\infrastructure\base\CallResult;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface PayOrderServiceInterface extends BaseServiceInterface
{

    /**
     *
     * @param string $subject 交易订单商品名称
     * @param string $clientId 分配的clientId
     * @param string $outOrderNo 第三方订单号
     * @param integer $amount 金额
     * @param string $payType 支付通道类型
     * @param string $callback 回调地址
     * @param string $returnUrl 同步回调地址
     * @param string $note 备注
     * @return CallResult
     */
    public function create($subject, $clientId, $outOrderNo, $amount, $payType, $callback, $returnUrl = '', $note = '');

    /**
     * 设置交易单为成功状态
     * @param string $payCode 系统交易订单号
     * @param string $tradeNo 支付机构方交易号
     * @param string $tradeStatus 支付机构交易状态
     * @param integer $payTime 支付时间
     * @param integer $notifyTime 通知时间
     * @param integer $notifyMoney 通知的支付金额
     * @param array $payload
     * @return mixed
     */
    public function setPaySuccess($payCode, $tradeNo, $tradeStatus, $payTime, $notifyTime, $notifyMoney, $payload = []);


    /**
     * 设置回调成功
     * @param $id
     * @param $time
     * @return mixed
     */
    public function setCallbackNotifySuccess($id, $time);


    /**
     * 发起支付通知,通知成功要自动更新状态
     *
     * @param PayOrder $payOrder
     * @return CallResult
     */
    public function notify(PayOrder $payOrder);
}
