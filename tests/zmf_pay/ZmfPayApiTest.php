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
        $_SERVER['XFT_PAY_APP_ID'] = '';
        $_SERVER['XFT_PAY_NOTIFY_URL'] = 'http://api.guoxuekong.com/notify.php';
        $xftPay = new XftPay();
        $xftPay->getConfig()->setAppId('1189816897244233728');
        $xftPay->getConfig()->setKey('17A746BF7DEE88BBD8EC7376EAB00E9E');
        $xftPay->getConfig()->setMerchantCode('1330100028739');
        $ret = $xftPay->getPayUrl(date('YmdH').rand(10000000, 99999999), 200);
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
