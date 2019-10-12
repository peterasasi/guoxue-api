<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\PayOrder;
use App\Entity\ReqPost;
use App\ServiceInterface\ClientsServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use App\ServiceInterface\ReqPostServiceInterface;
use by\component\encrypt\md5v4\Md5V4Transport;
use by\component\encrypt\rsa\Rsa;
use by\component\encrypt\rsav3\RSAV3Transport;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransportController extends AbstractController
{
    protected $logger;
    protected $reqPostService;
    protected $payOrderService;
    protected $clientsService;

    public function __construct(
        ClientsServiceInterface $clientsService,
        PayOrderServiceInterface $payOrderService, ReqPostServiceInterface $reqPostService, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->reqPostService = $reqPostService;
        $this->payOrderService = $payOrderService;
        $this->clientsService = $clientsService;
    }

    /**
     * 模拟商户端支付成功处理
     * @Route("/transport/callback", name="tV4_callback")
     * @param Request $request
     * @return string
     */
    public function callback(Request $request) {
        $entity = new ReqPost();
        $entity->setCreateTime(time());
        $entity->setUpdateTime(time());
        $entity->setParams(json_encode($request->request->all()));
        $this->reqPostService->add($entity);

        $param['pay_code'] = $request->request->get('pay_code', '');
        $param['money'] = $request->request->get('money', '');
        $param['trade_no'] = $request->request->get('trade_no', '');
        $param['order_no'] = $request->request->get('order_no', '');
        $param['client_id'] = $request->request->get('client_id', '');

        $payOrder = $this->payOrderService->info(['pay_code' => $param['pay_code']]);
        if (!$payOrder instanceof PayOrder) {
            $this->logger->error('pay_code invalid'.json_encode($param));
            return new Response('pay_code invalid');
        }

        $clientInfo = $this->clientsService->info(['client_id' => $param['client_id']]);
        if (!$clientInfo instanceof Clients) {
            $this->logger->error('client_id invalid'.json_encode($param));
            return new Response('client_id invalid');
        }

        $publicKey = $clientInfo->getSysPublicKey();
        $publicKey = Rsa::formatPublicText($publicKey);

        $sign = $request->request->get('sign', '');

        ksort($param);

        $data = json_encode($param, JSON_UNESCAPED_UNICODE);
//        var_dump($data);
//        var_dump($sign);

        $verify = Rsa::verifySign($data, $sign, $publicKey);
//var_dump($verify);
        if ($verify == 1) {
            return new Response('success');
        } else {
            return new Response('verify sign failed');
        }
    }

    /**
     * @Route("/transport/rsav3", name="rsav3_encrypt")
     * @throws \Exception
     */
    public function rsav3() {
        // 平台公钥
        $sysPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAss8fF/aD1v7Xd/8HWcGe
YjmV3GnCg2I8LMEzjn69fl/MIdA0Xjr3LHbHsG9JtzSgAntgurPLoFy/j7cUtmUe
K7T/p4F1RTAWBApkU0FApcXri3m8zUTUTsEq6TK1v06tGremOsMOvyyRVFc8bgU9
FSH94nRxIT6J6uqrIGQnha2vgm/Mv5JnXWpGvyX7AALokNo2KdjruG5gxa0MdKHR
xC0t5Kcy3a93U+iA5lxjRor9dUlt49GSxxNkwr94PJD601SkmTIh/5AubQBlLl/q
nxB0DkqG3PNAY+GdSEKFg711hhPTKHNKZNC3n/z5NtLloAUOF7IvUzV1ZpRI/cO5
NQIDAQAB";
        $sysPublicKey = str_replace("\n", "", $sysPublicKey);
// 用户私钥
        $userPrivateKey = "MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDH8NFjIzuaxLyCOieDz5icyNwg1mb/QnwzqO7z599rLVtZ2i+xfN805/ActRJJB8+D/PqoM7icA1r2oqZgYetLki8QP94Oqdxs8UzH73uWdbdb6zCOWYyxk2Jftjkp3zL97fqahwhOmY3mRaeN+dS+6REJ4IQVIHujmNRLHaUTaUNRbj/HfS6aV/SONAhSoj0/WkFOf4eXbAngvEv0Du3m/Fb7UCvMkZvj5xJ4G0mlbBmAK/m2bryfa9wcP0fnIli1ReJ1DFlm0Za5t+eUpZAQQfT3YaxbGRmXq8kiMJaPfGDYrzMLVsja2dIqFEXrglWQcMXtY+Ursd5JVpvdbEA/AgMBAAECggEAF5FGTSZBB1w7UCpkr//PYGO4ttIu79W5aCl4iR2C01JUW0IBry0l7kmMnwWk8yDNkCRIs3ztPM6UcU/4xpGkN5Myovq0RQw2pEzJHSQYcELN6zLM1Wquz9usk9WZ5Vqe2xmrGX3jN8iX1lXNi0mwjxRP4tcpGohqqn0AQR5sb94Z/hSvOcR4/kccXMQIGkhnejKqXkXzJOHMFGqBOWVThs4OSnAPhPlXVTAZ89sK00C+JZImYrqOyoSoaq52p2pQOe09OrIBdeGVSJ3GVFt+fIB0NTBPXG2xjZr0v2EYrNl1tGWcCqktWEEjKt08pV4D68Db2jQhf4Q7YzT1km2mcQKBgQD2fpPFE2Mb2uDrturbaFkLp6B7odIIwdDE1N50zPHk1qK+49WcMQHT0OFSZauUvdyqPmkgOI2A3cjf3Wdk4Tl2K3SeeVLdErZfqWRzDML3BPEjH5x531hR4gvpWMcN0GkmGKiWLRkCYMHcydO5Ibbz+WlYnRFCSgycVcumSOnNCQKBgQDPpqgQgrwIQEYAISPU/I/atwmV7EtbSBqw6EF3+vypOJhB0GqwUJsBa7A/IQSLkfnlwIquHdgrUR1FX1La6HM4Om8JMlSBZw96dlOOcPVtEZS7QPhQDV1c/dFfVAncD13oI5DBYObZ4dYY1beRo8MNwWvaCpC/uWPxiovk/2i9BwKBgQDQuKHb8OytO4vVTNBV9WfhTJHB3maBb8ydvzqXYKs7gNvSFA5e8ciAWZFSOjEuBA8EQVC3Lev0QNjFZy8T5vrHK0jWoBkghaXUHxWlrhqxHIgrm6reL9cTjvtTHg9/jQhcb+jhMVLKBrBhiq0zSG8o6/reRDHHFfjTsHp/VaJUMQKBgQDPCHy8qXxMRbkFXAVbz8yl5qT6A7RGeKeUBp1vwKC1H6Y+yEv3KwbA7du1tXfQqGSd+9DJNRxYY/FpP1dexzBJuYkHhFTZCCZYlS1N8bXhXwwJfweU2R5jHvXns+R4siGQ2BT1mWXRiudpr3vtC3foeRbNOIeFgJPzOY2tbjHBdQKBgQCGy+oclVfmtLdQ1w32kIUFSZmfsE0UWUIk5cTFfwZv46r5IDyCW32G+ud1Yj8nTl88FijmqrvDeYW6Jucu5/e09pv3VfOqdPYXWFB8O8VtcEopiesiQ35/0iduZf2CXTH+jSRzrJG1nw57dAZmvTigcw1EdfTOgesO691cG/I+LQ==";

        $data = [
            'notify_id' => '666666',
            'client_id' => 'by2204esfH0fdc6Y',
            'client_secret' => 'fcbe33277447fbd48343b68d1b3f8de0',
            'app_request_time' => '1530528254',
            'service_type' => 'by_Clients_query',
            'service_version' => '100',
            'app_type' => 'ios',
            'app_version' => '1.0.0',
            'lang' => 'zh-cn',
            'buss_data' => [
                'uid' => '2'
            ]
        ];
        $data['sys_public_key'] = Rsa::formatPublicText($sysPublicKey);
        $data['my_private_key'] = Rsa::formatPrivateText($userPrivateKey);
        $transport = new RSAV3Transport([]);
        return $transport->clientEncrypt($data);
    }

    /**
     * @Route("/transport/encryptV4", name="tV4_encrypt")
     */
    public function encryptV4() {
        $alg = new Md5V4Transport();
        $bussData = [
            'nickname' => 'nickname',
            'username' => 'username',
            'dddd_callback' => 'callabck',
            'callback' => 'callabck'
        ];

        $data = [
            'notify_id' => '1560419676614',
            'client_secret' => 'df45c46ca6df63e7d5b38bfb7d61b5fc',
            'app_request_time' => '1560419676614',
            'service_type' => 'by_UserLoginSession_updateInfo',
            'service_version' => '100',
            'app_type' => 'android',
            'app_version' => '1.0.0',
            'buss_data' => $bussData,
            'client_id' => 'by04esfI0fYuD5',
        ];
        return $alg->encrypt($data);
    }

    protected function facade($mobile, $money, $callback) {
        $alg = new Md5V4Transport();
        $now = strval(time());
        $data = [
            'client_id' => 'by04esfJ0fYuTb',
            'client_secret' => 'df45c46ca6df63e7d5b38bfb7d61b5fc',
            'notify_id' => $now,
            'app_request_time' => $now,
            'service_type' => 'by_UserAddress_info',
            'service_version' => '100',
            'app_type' => 'php_server',
            'app_version' => '1.0.0',
            'buss_data' => ['id' => 1]
        ];
        return $alg->encrypt($data);
    }

}
