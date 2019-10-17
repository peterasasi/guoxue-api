<?php


namespace by\component\message_sender\impl;


use by\component\http\HttpRequest;
use by\component\message_sender\interfaces\SenderInterface;
use by\component\security_code\constants\SecurityCodeType;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;

class SubmailSmsSender implements SenderInterface
{
    protected $url;
    protected $appkey;
    protected $appid;
    protected $code;
    protected $to;
    protected $content;


    /**
     * [
     *   'appid' => 'appid',
     *   'content' => '【SUBMAIL】您的短信验证码：#code#',
     * ]
     * SubmailSmsSender constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->url = "https://api.mysubmail.com/message/send.json";

        if (is_array($data) && array_key_exists('appid', $data) && array_key_exists('appkey', $data)) {
            $this->appid = $data['appid'];
            $this->appkey = $data['appkey'];
        } else {
            throw new \InvalidArgumentException(('submail sms config error'));
        }
        $this->code = $data['code'];
        $this->to = $data['mobile'];
        $this->content = str_replace('#code#', $this->code, $data['content']);
//        【SUBMAIL】您的短信验证码：4438，请在10分钟内输入。
    }

    public function send()
    {

        $data = [
            'appid' => $this->appid,
            'to' => $this->to,
            'sign_type' => 'normal',
            'content' => $this->content,
            'signature' => $this->appkey
        ];

        $http = new HttpRequest();
        $resp = $http->timeout(30000, 10000)->post($this->url, $data);

        if (!$resp->success) {
            return CallResultHelper::fail($resp->getError());
        }
        $content = $resp->getBody()->getContents();

        if ($content) {
            $result = json_decode($content, true);
//            {
//                "status":"success"
//    "send_id":"093c0a7df143c087d6cba9cdf0cf3738"
//    "fee":1,
//    "sms_credits":14197
//}
            $status = $result['status'];
            if ($status == 'success') {
                //状态为0，说明短信发送成功
                return CallResultHelper::success("sms send success");
            } else {
                //状态非0，说明失败
                $msg = $result['msg'];
                return CallResultHelper::fail("短信发送失败(" . $result['code'] . ")：" . $msg);
            }
        } else {
            //返回内容异常，以下可根据业务逻辑自行修改
            return CallResultHelper::fail("短信发送失败");
        }
    }
}
