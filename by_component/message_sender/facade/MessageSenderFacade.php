<?php

namespace by\component\message_sender\facade;


use by\component\message_sender\constants\MessageSenderTypeEnum;
use by\component\message_sender\impl\AlertMessageSender;
use by\component\message_sender\impl\AliyunSmsSender;
use by\component\message_sender\impl\JuheMessageSender;
use by\component\message_sender\impl\PushUMengMessageSender;
use by\component\message_sender\impl\QcloudMessageSender;
use by\component\message_sender\impl\SubmailSmsSender;
use by\component\message_sender\interfaces\SenderInterface;
use by\infrastructure\helper\CallResultHelper;

class MessageSenderFacade
{

    /**
     * @var SenderInterface
     */
    private static $sender;

    public static function create($type, $data)
    {
        switch ($type) {
            case MessageSenderTypeEnum::SMS_QCLOUD:
                self::$sender = new QcloudMessageSender($data);
                break;
            case MessageSenderTypeEnum::SMS_JUHE:
                self::$sender = new JuheMessageSender($data);
                break;
            case MessageSenderTypeEnum::PUSH_UMENG:
                self::$sender = new PushUMengMessageSender($data);
                break;
            case MessageSenderTypeEnum::SMS_Aliyun:
                self::$sender = new AliyunSmsSender($data);
                break;
            case MessageSenderTypeEnum::SMS_Submail:
                self::$sender = new SubmailSmsSender($data);
                break;
            default:
                self::$sender = new AlertMessageSender($data);
                break;
        }
        return self::$sender;
    }

    public static function send()
    {
        if (self::$sender instanceof SenderInterface) {
            return self::$sender->send();
        }
        return CallResultHelper::fail('fail');
    }
}
