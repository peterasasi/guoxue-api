<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2019-05-29
 * Time: 17:51
 */

namespace byTest\component\zmf_pay;

use by\component\encrypt\rsa\Rsa;
use by\component\usdt_pay\UsdtPay;
use by\component\zmf_pay\common\ZmfDevConfig;
use by\component\zmf_pay\common\ZmfProductCode;
use by\component\zmf_pay\common\ZmfTools;
use by\component\zmf_pay\req\ZmfBankBranchQueryReq;
use by\component\zmf_pay\req\ZmfCustomerBankQueryReq;
use by\component\zmf_pay\req\ZmfFourFactorsAuthReq;
use by\component\zmf_pay\req\ZmfProductQueryReq;
use by\component\zmf_pay\ZmfPayApi;
use by\infrastructure\base\CallResult;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ZmfPayApiTest extends TestCase
{

    public function testIndex() {
        $url = (new UsdtPay())->getPayUrl('1', '500');
        var_dump($url);
    }

}
