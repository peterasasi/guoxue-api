<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Common\CacheKeys;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\ByCacheKeys;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    protected $cache;

    public function __construct(AdapterInterface $adapter)
    {
        $this->cache = $adapter;
    }

    /**
     * @Route("/cache/clear", name="Cache_clear")
     * @return \by\infrastructure\base\CallResult
     */
    public function clear() {
        return CallResultHelper::success($this->cache->clear());
    }

    /**
     * @Route("/cache/clear_switch", name="Cache_clearSwitch")
     * @return \by\infrastructure\base\CallResult
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function clearSwitch() {
        return CallResultHelper::success($this->cache->deleteItem(ByCacheKeys::PaymentChannelSwitch));
    }
}
