<?php

namespace by\component\message_sender\impl;


use by\component\lang\helper\LangHelper;
use by\component\message_sender\interfaces\SenderInterface;
use by\infrastructure\helper\CallResultHelper;

class PushUMengMessageSender implements SenderInterface
{
    public function __construct($config)
    {
    }

    public function send()
    {
        return CallResultHelper::fail(LangHelper::lang('{:thing} not implement',['thing'=>'友盟推送']));
    }

}
