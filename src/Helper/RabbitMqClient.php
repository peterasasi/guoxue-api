<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Helper;


use App\Common\ByEnv;
use by\component_3rdQueueClient\AmqpRabbitClient;

class RabbitMqClient extends AmqpRabbitClient
{
    public function __construct()
    {
        $host = ByEnv::get('AMQP_HOST');
        $port = ByEnv::get('AMQP_PORT');
        $user = ByEnv::get('AMQP_USER');
        $pass = ByEnv::get('AMQP_PASS');
        $vhost = ByEnv::get('AMQP_VHOST');
        parent::__construct($host, $port, $user, $pass, $vhost);
    }

    public function receive($exchangeName, $id, $callback = null,  $prefetchCount = 1)
    {
        $this->consumer($exchangeName, $exchangeName . $id, [
            "callback" => $callback
        ], $prefetchCount);
    }


    public function init($exchangeName, $durable = true) {
        $this->openConnection();
        $this->bindQueueAndExchange($exchangeName, $exchangeName, ['durable' => $durable], ['durable' => $durable]);
        return $this;
    }

    /**
     * @param string $exchangeName
     * @param $content
     */
    public function send($exchangeName, $content) {
        $this->publish($exchangeName, json_encode($content));
    }

}
