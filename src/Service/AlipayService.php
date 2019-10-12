<?php


namespace App\Service;


use App\Common\CacheKeys;
use App\Entity\Config;
use App\ServiceInterface\AlipayServiceInterface;
use App\ServiceInterface\ConfigServiceInterface;
use App\ServiceInterface\PayOrderServiceInterface;
use by\component\config\ConfigParser;
use by\component\exception\InvalidArgumentException;
use by\component\proxyPay\Pay;
use by\component\proxyPay\Supports\Collection;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class AlipayService
 * 需要配置
 * app_id: //支付宝应用id
 * mode:dev // normal:正式 dev:沙箱模式
 * notify_url: // 异步回调地址
 * return_url: // 同步回调地址
 * ali_public_key: // 支付宝的公钥
 * private_key:// 商户的私钥
 * @package App\Service
 */
class AlipayService implements AlipayServiceInterface
{

    protected $config = [
        'app_id' => '', //
        'mode' => 'dev', // normal:正式 dev:沙箱模式
        'notify_url' => '',
        'return_url' => '',
        'ali_public_key' => '',
        // 加密方式： **RSA2**
        'private_key' => '',
        'log' => [ // optional
            'file' => '',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'verify' => false,
            'timeout' => 10.0,
            'connect_timeout' => 10.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ]
    ];
    protected $payOrderService;
    protected $configService;
    protected $orderService;
    /**
     * @var LoggerInterface
     */
    private $logger;
    protected $alipay;

    /**
     * AlipayService constructor.
     * @param PayOrderServiceInterface $payOrderService
     * @param CacheItemPoolInterface $cache
     * @param ConfigServiceInterface $configService
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     * @throws InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __construct(
        PayOrderServiceInterface $payOrderService, CacheItemPoolInterface $cache,
        ConfigServiceInterface $configService, LoggerInterface $logger, KernelInterface $kernel)
    {
        $this->payOrderService = $payOrderService;
        $this->logger = $logger;
        $this->config['log']['file'] = $kernel->getLogDir() . '/alipay.log';
        $this->configService = $configService;
        $cacheItem = $cache->getItem(CacheKeys::AppAlipayConfig);
        if ($cacheItem->isHit()) {
            $this->config = json_decode($cacheItem->get(), JSON_OBJECT_AS_ARRAY);
        } else {
            $this->initConfig();
        }
        $this->alipay = Pay::alipay($this->config);
    }

    /**
     * @return Collection
     * @throws \by\component\proxyPay\Exceptions\InvalidConfigException
     * @throws \by\component\proxyPay\Exceptions\InvalidSignException
     */
    public function verify()
    {
        return $this->alipay->verify();
    }

    public function success()
    {
        return $this->alipay->success();
    }


    public function getConfig($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return '';
    }

    public function initConfig()
    {
        $cfg = $this->configService->info(['cate' => 7, 'name' => 'ALIPAY_API']);
        if ($cfg instanceof Config) {
            $cfgValue = ConfigParser::parse($cfg->getType(), $cfg->getValue());
            $arr = ['app_id', 'mode', 'notify_url', 'return_url', 'ali_public_key', 'private_key'];
            foreach ($arr as $key) {
                if (empty($cfgValue[$key])) {
                    throw new InvalidArgumentException('AliPay Config Error ' . $key);
                }
                $this->config[$key] = $cfgValue[$key];
            }
        }
        return $this->config;
    }

    /**
     * @param $payCode
     * @return \by\component\proxyPay\Supports\Collection
     * @throws \by\component\proxyPay\Exceptions\GatewayException
     * @throws \by\component\proxyPay\Exceptions\InvalidConfigException
     * @throws \by\component\proxyPay\Exceptions\InvalidSignException
     */
    public function query($payCode): Collection
    {
        return $this->alipay->find($payCode);
    }

    /**
     * @param $refundInfo
     * @return \by\component\proxyPay\Supports\Collection
     * @throws \by\component\proxyPay\Exceptions\GatewayException
     * @throws \by\component\proxyPay\Exceptions\InvalidConfigException
     * @throws \by\component\proxyPay\Exceptions\InvalidSignException
     */
    public function refund($refundInfo): Collection
    {
        return $this->alipay->refund($refundInfo);
    }

    public function web($order)
    {
        return $this->alipay->web($order);
    }

    public function wap($order)
    {
        return $this->alipay->wap($order);
    }

}
