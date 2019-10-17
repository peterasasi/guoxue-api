<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2019-05-29
 * Time: 17:51
 */

namespace byTest\component\zmf_pay;

use by\component\message_sender\impl\SubmailSmsSender;
use by\component\usdt_pay\UsdtPay;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ZmfPayApiTest extends TestCase
{

    public function testIndex() {
        $url = (new UsdtPay())->getPayUrl('1', '500');
        var_dump($url);
    }

    public function testSms() {
        $data = [
            'appid' => '',
            'appkey' => '',
            'code' => '123456',
            'content' => '【SUBMAIL】您的短信验证码：#code#，请在10分钟内输入。',
            'mobile' => '12345678900',
        ];
        $ret = (new SubmailSmsSender($data))->send();
        var_dump($ret);
    }

}
