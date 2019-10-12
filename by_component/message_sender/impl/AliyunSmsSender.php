<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/16
 * Time: 15:39
 */

namespace by\component\message_sender\impl;


use by\component\message_sender\interfaces\SenderInterface;
use by\component\sms\aliyun\AliyunSmsManage;

class AliyunSmsSender implements SenderInterface
{
    protected $data = [
        'phone'=>'18557515452',
        'sign' => '登录验证',
        'template' => 'SMS_8145826',
        'template_params' => '{"customer":"何必都"}',
        'access_key_id' => '',
        'access_key_secret' => '',
        'region' => 'cn-beijing',
        'end_point_name' => 'cn-beijing',
        'api_uri' => 'dysmsapi.aliyuncs.com',
    ];
    /**
     * @var AliyunSmsManage
     */
    protected $smsManage;

    public function __construct($data)
    {
        $this->data = $data;
        $this->data['phone'] = $data['mobile'];
        unset($data['sign']);
        unset($data['scene']);
        unset($data['project_id']);
        unset($data['mobile']);
        unset($data['access_key_id']);
        unset($data['access_key_secret']);
        unset($data['region']);
        unset($data['end_point_name']);
        unset($data['api_uri']);
        unset($data['template']);
        $tplValue = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->data['template_params'] = $tplValue;
        $this->smsManage = new AliyunSmsManage();
        $this->smsManage->setData($this->data);
    }

    public function send()
    {
        return $this->smsManage->send();
    }

}
