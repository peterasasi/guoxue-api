<?php

namespace by\component\message_sender\constants;

/**
 * Class CodeSendTypeEnum
 *
 * @package by\component\message_sender\constants
 */
class MessageSenderTypeEnum
{
    /**
     *  只是返回
     */
    const ALERT = "alert";

    /**
     * 短信息 - 腾讯云
     */
    const SMS_QCLOUD = "sms_qcloud";

    /**
     * 短信息 - 聚合
     */
    const SMS_JUHE = "sms_juhe";

    /**
     * 推送信息 - 友盟
     */
    const PUSH_UMENG = "push_umeng";

    /**
     * 阿里云
     */
    const SMS_Aliyun = "sms_aliyun";
}
