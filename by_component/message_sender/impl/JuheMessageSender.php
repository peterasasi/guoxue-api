<?php

namespace by\component\message_sender\impl;


use by\component\config\ConfigStorageInterface;
use by\component\message_sender\interfaces\SenderInterface;
use by\infrastructure\helper\CallResultHelper;

class JuheMessageSender implements SenderInterface
{
    private $sendUrl;
    private $config = [
        'key'=>'',
        'mobile'=>'',
        'tpl_id'=>'',
        'tpl_value'=>'',
    ];

    /**
     * JuheMessageSender constructor.
     * @param $data
     * @param ConfigStorageInterface $configStorage
     */
    public function __construct($data)
    {
        $this->sendUrl = "http://v.juhe.cn/sms/send";
        if (is_array($data) && array_key_exists('tpl_id', $data) && array_key_exists('app_key', $data)) {
            $this->config['tpl_id'] = $data['tpl_id'];
            $this->config['key'] = $data['app_key'];
        } else {
            throw new \InvalidArgumentException(('juhe sms config error'));
        }
        $this->config['mobile'] = $data['mobile'];
        unset($data['scene']);
        unset($data['project_id']);
        unset($data['mobile']);
        $tplValue = "";
        foreach ($data as $key => $vo)
        {
            if (strlen($tplValue) > 0) {
                $tplValue .= "&";
            }
            $tplValue .= "#".$key."#=".(strval($vo));
        }
        $this->config['tpl_value'] = urlencode($tplValue);
    }

    public function send()
    {
        $result = SmsManage::instance($this->config, $this->sendUrl)->send();
        if ($result->isSuccess()) {
            return CallResultHelper::success(('sms send success'));
        } else {
            return CallResultHelper::fail($result->getMsg(), $result->getData());
        }
    }

}
