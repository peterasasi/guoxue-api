<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 10:53
 */

namespace App\Command;


use App\Common\RabbitMqConstants;
use App\Dto\CfOrderPaySuccessDto;
use App\Entity\CfPayNotify;
use App\Entity\CfPayOrder;
use App\Helper\RabbitMqClient;
use App\ServiceInterface\CfPayNotifyServiceInterface;
use App\ServiceInterface\CfPayOrderServiceInterface;
use by\infrastructure\helper\Object2DataArrayHelper;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PayNotifyFirstCommand
 * 按ClientId进行区分不同渠道
 * @package App\Command
 */
class PayNotifyFirstCommand extends Command
{

    /**
     * @var RabbitMqClient
     */
    private $client;

    private $logger;

    public function __construct(LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    public function process($data)
    {
        $channel = $data->delivery_info['channel'];
        $deliveryTag = $data->delivery_info['delivery_tag'];
        if (!$channel instanceof AMQPChannel) {
            return;
        }
        try {
            while (!$channel->getConnection()->isConnected()) {
                $channel->getConnection()->reconnect();
                echo "reconnect", "\n";
                sleep(1);
            }
            $msgContent = $data->body;
            $this->logger->debug('[MQ_PROCESS]' . $msgContent);
            if (strpos($msgContent, "\\\"") === false) {
                $msgContent = str_replace('\\', "", $msgContent);
            }
            $msgContent = json_decode($msgContent, JSON_OBJECT_AS_ARRAY);

            if (is_array($msgContent)) {
                if (array_key_exists('unique_order', $msgContent)) {
                    $payCode = $msgContent['unique_order'];

//                    $notify = $this->payNotifyService->info(['pay_code' => $payCode]);
//                    if ($notify instanceof CfPayNotify) {
//                        $ret = $this->payNotifyService->notify($notify->getId(), $notify);
//                        if ($ret->isFail()) {
//                            $this->logger->debug('payNotifyFirst.ERROR' . $ret->getMsg());
//                        } else {
//                            $this->logger->debug('notify_success');
//                        }
//                    }
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error('payNotifyFirst.ERROR' . $exception->getMessage());
        } finally {
            $channel->basic_ack($deliveryTag, true);
        }
    }

    protected function configure()
    {
        $this->setName("pay:first")
            ->setDescription("pay first notify command")
            ->setHelp('pay first notify');
        $this->addOption('type', 't', InputArgument::OPTIONAL, '');
        $this->addOption('code', 'c', InputArgument::OPTIONAL, '');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');
        $code = $input->getOption('code');

        // 进行消费
        $this->client = new RabbitMqClient();
        $this->client->init(RabbitMqConstants::ExchangeOrder);
        if ($type == 'send') {
            // 用来测试
            $payCode = $code;
//            $payOrder = $this->payOrderService->info(['pay_code' => $payCode]);
//            if ($payOrder instanceof CfPayOrder) {
//                $payload = ['order_code' => $payOrder->getOrderCode(), 'client_id' => $payOrder->getClientId(), 'merchant_code' => $payOrder->getMerchantCode()];
//                $dto = new CfOrderPaySuccessDto();
//                $dto->setPayType(CfPayOrder::PayTypeOfAliPayPc);
//                $dto->setPayTime($payOrder->getNotifyTime());
//                $dto->setMoney($payOrder->getMoney());
//                $dto->setNotifyUrl($payOrder->getCallback());
//                $dto->setPayload($payload);
//                $dto->setTradeNo($payOrder->getTradeNo());
//                $dto->setUniqueOrder($payCode);
//
//                $this->client->send(RabbitMqConstants::ExchangeOrder, Object2DataArrayHelper::getDataArrayFrom($dto));
//            }
        } else {
            $this->client->receive(RabbitMqConstants::ExchangeOrder, 0, [$this, "process"], 1);
        }
    }
}
