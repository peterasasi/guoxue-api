<?php


namespace App\Service;


use App\Entity\Clients;
use App\Entity\PayOrder;
use App\Entity\PayOrderNotifyLog;
use App\Helper\CodeGenerator;
use App\Repository\PayOrderNotifyLogRepository;
use App\Repository\PayOrderRepository;
use App\ServiceInterface\ClientsServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use by\component\encrypt\rsa\Rsa;
use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Doctrine\DBAL\LockMode;

class PayOrderService extends BaseService implements PayOrderServiceInterface
{
    protected $clientService;
    protected $notifyLogRepository;

    public function __construct(
        PayOrderNotifyLogRepository $notifyLogRepository,
        ClientsServiceInterface $clientsService, PayOrderRepository $repository)
    {
        $this->repo = $repository;
        $this->clientService = $clientsService;
        $this->notifyLogRepository = $notifyLogRepository;
    }

    protected function checkIfExists($clientId, $outOrderNo)
    {
        $payOrder = $this->info(['client_id' => $clientId, 'out_order_no' => $outOrderNo]);
        return ($payOrder instanceof PayOrder);
    }

    /**
     * @param $subject
     * @param string $clientId
     * @param string $outOrderNo
     * @param int $amount
     * @param string $payType
     * @param string $callback
     * @param string $returnUrl
     * @param string $note
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($subject, $clientId, $outOrderNo, $amount, $payType, $callback, $returnUrl = '', $note = '')
    {
        if ($this->checkIfExists($clientId, $outOrderNo)) {
            return CallResultHelper::fail('订单号重复, 请不要重复创建订单');
        }

        $entity = new PayOrder();
        $payCode = CodeGenerator::payCodeByClientId($clientId);

        $entity->setReturnUrl($returnUrl);
        $entity->setSubject($subject);
        $entity->setPayCode($payCode);
        $entity->setClientId($clientId);
        $entity->setOutOrderNo($outOrderNo);
        $entity->setMoney($amount);
        $entity->setPayType($payType);
        $entity->setCallback($callback);
        $entity->setNote($note);

        return CallResultHelper::success($this->add($entity));
    }

    public function setPaySuccess($payCode, $tradeNo, $tradeStatus, $payTime, $notifyTime, $notifyMoney, $payload = [])
    {
        $payOrder = $this->info(['pay_code' => $payCode]);
        if (!($payOrder instanceof PayOrder)) {
            return CallResultHelper::fail('pay code invalid');
        }

        // 同时满足，则认为已经处理过了，无需重复处理
        if ($payOrder->getPayStatus() == PayOrder::PayStatusSuccess) {
            return CallResultHelper::fail('already paid', '', -3);
        }

        $em = $this->repo->getEntityManager();
        $em->beginTransaction();
        try {

            $payOrder = $this->repo->find($payOrder->getId(), LockMode::PESSIMISTIC_WRITE);
            if (!($payOrder instanceof PayOrder)) {
                return CallResultHelper::fail('pay code invalid');
            }

            if ($payOrder->getPayStatus() == PayOrder::PayStatusSuccess) {
                return CallResultHelper::fail('already paid', '', -3);
            }

            // 1. 设置支付订单相关信息
            $payOrder->setPayTime($payTime);
            $payOrder->setNotifyTime($notifyTime);
            $payOrder->setTradeStatus($tradeStatus);
            $payOrder->setNotifyMoney($notifyMoney);
            $payOrder->setTradeNo($tradeNo);
            $payOrder->setPayStatus(PayOrder::PayStatusSuccess);
            $payOrder->setPayLoad(json_encode($payload));
            $payOrder->setCallbackStatus(0);

            $this->flush($payOrder);
            $em->commit();
            return CallResultHelper::success();
        } catch (\Exception $e) {
            $em->rollback();
            return CallResultHelper::fail('PayOrder异常-' . $e->getMessage());
        }
    }

    public function setCallbackNotifySuccess($id, $time)
    {

        $em = $this->repo->getEntityManager();
        $em->beginTransaction();
        try {

            $payOrder = $this->repo->find($id, LockMode::PESSIMISTIC_WRITE);
            if (!($payOrder instanceof PayOrder)) {
                return CallResultHelper::fail('id invalid');
            }

            if ($payOrder->getCallbackStatus() == PayOrder::PayStatusSuccess) {
                return CallResultHelper::fail('already notify success', '', -3);
            }

            // 1. 设置支付订单相关信息
            $payOrder->setCallbackStatus(PayOrder::CallbackStatusSuccess);
            $payOrder->setCallbackNotifyTime(time());

            $this->flush($payOrder);
            $em->commit();
            return CallResultHelper::success();
        } catch (\Exception $e) {
            $em->rollback();
            return CallResultHelper::fail('setCallbackNotifySuccess-' . $e->getMessage());
        }
    }

    /**
     * @param PayOrder $payOrder
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\encrypt\exception\CryptException
     */
    public function notify(PayOrder $payOrder)
    {
        if ($payOrder->getPayStatus() !== PayOrder::PayStatusSuccess) {
            return CallResultHelper::fail('该订单还没支付成功');
        }
        $url = $payOrder->getCallback();
        if (!empty($url)) {
            // http请求
            $body = [
                'pay_code' => $payOrder->getPayCode(),
                'money' => strval($payOrder->getMoney()),
                'trade_no' => $payOrder->getTradeNo(),
                'order_no' => $payOrder->getOutOrderNo(),
                'client_id' => $payOrder->getClientId()
            ];

            $clients = $this->clientService->info(['client_id' => $payOrder->getClientId()]);

            if (!$clients instanceof Clients) {
                return CallResultHelper::fail('[不该出现的错误] client_id 不存在');
            }

            $sysPrivateRsa = Rsa::formatPrivateText($clients->getSysPrivateKey());
            $userPublicRsa = Rsa::formatPrivateText($clients->getUserPublicKey());

            ksort($body);

            $strBody = json_encode($body, JSON_UNESCAPED_UNICODE);

            $body['alg'] = 'rsa_v2';
            $body['sign'] = Rsa::sign($strBody, $sysPrivateRsa);

            $strBody = json_encode($body, JSON_UNESCAPED_UNICODE);
            $body['content'] = Rsa::encryptChunk($strBody, $userPublicRsa);

            unset($body['sign']);
            unset($body['order_no']);
            unset($body['trade_no']);
            unset($body['pay_code']);
            unset($body['money']);

            // 增加回调次数，不控制并发影响
            $payOrder->setCallbackCnt($payOrder->getCallbackCnt() + 1);
            $this->flush($payOrder);

            $ret = HttpRequest::newSession()->post(urldecode($url), $body);
            if ($ret->success) {
                $content = $ret->getBody()->getContents();
                if (strtolower($content) === 'success') {
                    $this->setCallbackNotifySuccess($payOrder->getId(), time());
                    return CallResultHelper::success();
                } else {
                    $msg = '第三方返回信息:' . $content;
                }
            } else {
                $msg = 'HTTP请求错误:' . $ret->getError();
            }
            $this->notifyLog($payOrder->getPayCode(), $payOrder->getCallbackCnt() + 1, $msg);

            return CallResultHelper::fail($msg);
        }
        return CallResultHelper::success();
    }


    /**
     * @param $payCode
     * @param $notifyCnt
     * @param $msg
     * @param bool $success
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function notifyLog($payCode, $notifyCnt, $msg, $success = false)
    {
        $entity = new PayOrderNotifyLog();
        $entity->setPayCode($payCode);
        $entity->setNotifyMsg($msg);
        $entity->setSuccess($success ? 1 : 0);
        $entity->setNotifyCount($notifyCnt);
        $this->notifyLogRepository->add($entity);
    }
}
