<?php


namespace App\MessageHandler;


use App\Entity\PayOrder;
use App\Message\PaySuccessMsg;
use App\ServiceInterface\PayOrderServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class PaySuccessMsgHandler
 * 支付消息处理
 * 考虑增加定时任务来辅助处理，保证所有订单都能处理完成
 * @package App\MessageHandler
 */
class PaySuccessMsgHandler implements MessageHandlerInterface
{
    protected $payOrderService;
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        PayOrderServiceInterface $payOrderService)
    {
        $this->payOrderService = $payOrderService;
        $this->logger = $logger;
    }

    public function __invoke(PaySuccessMsg $paySuccessMsg)
    {
        try {
//            var_dump($paySuccessMsg);
            $payCode = $paySuccessMsg->getPayCode();
            $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);
            if (!$payOrder instanceof PayOrder) {
                $this->logger->debug('支付单号不存在');
                return;
            }

            if ($payOrder->getCallbackStatus() == PayOrder::CallbackStatusSuccess) {
                $this->logger->debug('支付单已经通知成功');
                return;
            }

            if ($payOrder->getCallbackStatus() == PayOrder::CallbackStatusFailed) {
                $this->logger->debug('支付单已经失败');
                return;
            }

            if ($payOrder->getPayStatus() != PayOrder::PayStatusSuccess) {
                $this->logger->debug('该订单尚未支付成功');
                throw new \Exception('该订单尚未支付成功');
            }
            // 超过3天以上的不处理
            if ($payOrder->getNotifyTime() > 0 && time() - $payOrder->getNotifyTime() > 3 * 24 * 3600) {
                $this->logger->debug('该订单已过期');
                return;
            }

            $notifyResult = $this->payOrderService->notify($payOrder);
//            var_dump($notifyResult);
            if ($notifyResult->isFail()) {
                $this->logger->error('支付通知错误-' . $notifyResult->getMsg());
                throw new \Exception($notifyResult->getMsg());
            }
        } catch (\Exception $exception) {
            $this->logger->error('[PaySuccessMsgHandler]异常' . $exception->getTraceAsString());
            throw $exception;
        }
    }

}
