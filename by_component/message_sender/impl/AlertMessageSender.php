<?php

namespace by\component\message_sender\impl;


use by\component\message_sender\interfaces\SenderInterface;
use by\infrastructure\helper\CallResultHelper;

/**
 * Class AlertMessageSender
 * 仅返回配置消息
 * @package by\component\message_sender\impl
 */
class AlertMessageSender implements SenderInterface
{
    private $code;

    public function __construct($config)
    {
        if (array_key_exists('code', $config)) {
            $this->code = $config['code'];
        }
    }


    // construct

    public function send()
    {
        $msg = ["your code is %code%", ['%code%'=>$this->code]];
        return CallResultHelper::success($this->code, $msg);
    }
}
