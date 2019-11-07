<?php


namespace App\Common;


use App\Entity\Config;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\config\ConfigParser;
use Psr\Cache\CacheItemPoolInterface;

class GxGlobalConfig
{
    const VIP1_HALF = 200;//vip1 一半的钱
    const InviteMinUsers = 0;// 不用邀请人
    const ConfigName = "gx_config";
    const ConfigCacheKey = "cache_".self::ConfigName;
    const ConfigCacheTime = 3600;

    protected $configService;
    protected $itemPool;
    protected $config;

    public function __construct(CacheItemPoolInterface $itemPool, ConfigServiceInterface $configService)
    {
        $this->itemPool = $itemPool;
        $this->configService = $configService;
        $this->config = [];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getMaxIncome(): int {
        return $this->config['max_income'];
    }
    public function getPlatformFixedProfit(): int {
        return $this->config['platform_fixed_profit'];
    }

    public function getPayFee(): float {
        return $this->config['pay_fee'];
    }

    public function getVip1(): int {
        return intval($this->config['vip1']);
    }

    public function getVipUpgrade(): int {
        return intval($this->config['vip_upgrade']);
    }

    public function init($projectId): self {
        $item = $this->itemPool->getItem(self::ConfigCacheKey);
        if (!$item->isHit()) {
            $gxCfg = $this->configService->info(['name' => GxGlobalConfig::ConfigName, 'project_id' => $projectId, 'status' => 1]);
            if ($gxCfg instanceof Config) {
                $value = ConfigParser::parse($gxCfg->getType(), $gxCfg->getValue());
                //max_income:5900000
                //platform_fixed_profit:99
                //pay_fee:0.005
                //vip1:499
                //vip_upgrade:200
                $item->set(json_encode($value));
                $item->expiresAfter(self::ConfigCacheTime);
                $this->itemPool->save($item);
            } else {
                throw new \Exception('gx config invalid');
            }
        } else {
            $value = json_decode($item->get(), JSON_OBJECT_AS_ARRAY);
        }
        $this->config = $value;
        return $this;
    }
}
