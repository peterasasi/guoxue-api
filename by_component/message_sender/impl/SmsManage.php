<?php

namespace by\component\message_sender\impl;

use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;

class SmsManage
{
    private static $instance = null;
    protected $url = "http://v.juhe.cn/sms/send";
    protected $cfg = [
        'key'=>'',
        'mobile' => '',
        'tpl_id' => '',
        'tpl_value' => '',
    ];

    public function __construct($cfg = [], $url = "http://v.juhe.cn/sms/send")
    {
        if (!empty($cfg)) {
            $this->cfg = $cfg;
        }
        $this->url = $url;
    }

    public static function instance($cfg = [], $url = "http://v.juhe.cn/sms/send"){
        if (self::$instance == null) {
            self::$instance = new SmsManage($cfg, $url);
        }
        return self::$instance;
    }

    public function setData($data)
    {
        $this->cfg = $data;
        return $this;
    }


    /**
     * 发送
     * @return \by\infrastructure\base\CallResult
     */
    public function send()
    {
        return $this->sendSms($this->cfg);
    }


    /**
     * config参数格式
     *
     * @example
     *  array(
     *   'key'   => '*****************', //您申请的APPKEY
     *   'mobile'    => '1891351****', //接受短信的用户手机号码
     *   'tpl_id'    => '111', //您申请的短信模板ID，根据实际情况修改
     *   'tpl_value' =>'#code#=1234&#company#=聚合数据' //您设置的模板变量，根据实际情况修改
     *   );
     * @param array $data
     * @return \by\infrastructure\base\CallResult
     */
    public function sendSms($data)
    {
        $http = new HttpRequest();
        $resp = $http->timeout(30000, 10000)->post($this->url, $data);

        if (!$resp->success) {
            return CallResultHelper::fail($resp->getError());
        }
        $content = $resp->getBody()->getContents();

        if ($content) {
            $result = json_decode($content, true);
            $error_code = $result['error_code'];
            if ($error_code == 0) {
                //状态为0，说明短信发送成功
                return CallResultHelper::success("sms send success");
            } else {
                //状态非0，说明失败
                $msg = $result['reason'];
                return CallResultHelper::fail("短信发送失败(" . $error_code . ")：" . $msg);
            }
        } else {
            //返回内容异常，以下可根据业务逻辑自行修改
            return CallResultHelper::fail("短信发送失败");
        }
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function sendAll()
    {
        throw new \Exception("not implements");
    }

}
