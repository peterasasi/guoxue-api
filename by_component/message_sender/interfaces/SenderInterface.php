<?php

namespace by\component\message_sender\interfaces;


use by\infrastructure\base\CallResult;

interface SenderInterface
{
    /**
     * @return CallResult
     */
    public function send();
}
