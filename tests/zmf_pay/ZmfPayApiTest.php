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
use by\component\xft_pay\XftPay;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ZmfPayApiTest extends TestCase
{

    public function testIndex() {
//        $url = (new UsdtPay())->getPayUrl('1', '500');
//        var_dump($url);

        $_SERVER['XFT_PAY_CLIENT_IP'] = '18.163.59.49';
        $_SERVER['XFT_PAY_APP_ID'] = '1184302811421081600';
        $_SERVER['XFT_PAY_M_CODE'] = '1522700024933';
        $_SERVER['XFT_PAY_KEY'] = 'FFB53C0BF7B9C67D2B5AFC5EA58C76C3';
        $_SERVER['XFT_PAY_NOTIFY_URL'] = 'http://api.guoxuekong.com/pay/notify/xft';
        $ret = (new XftPay())->getPayUrl(date('YmdH').rand(10000000, 99999999), 500);
        var_dump($ret);
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
