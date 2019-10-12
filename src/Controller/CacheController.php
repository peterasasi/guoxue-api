<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Common\CacheKeys;
use by\infrastructure\helper\CallResultHelper;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class CacheController extends AbstractController
{
    protected $cache;
    protected $cacheItemPool;

    public function __construct(AdapterInterface $adapter, CacheItemPoolInterface $cacheItemPool)
    {
        $this->cache = $adapter;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     *
     * @Route("/cache/clear/{key}", name="Cache_clear")
     * @param $key
     * @return \Psr\Cache\CacheItemInterface|string
     * @throws InvalidArgumentExceptionAlias
     */
    public function clearItem($key) {
        return $this->cacheItemPool->deleteItem($key);
    }
    /**
     *
     * @Route("/cache/get/{key}", name="Cache_get")
     * @param $key
     * @return \Psr\Cache\CacheItemInterface|string
     * @throws InvalidArgumentExceptionAlias
     */
    public function getItem($key) {
        $item = $this->cacheItemPool->getItem($key);

        if ($item->isHit()) {
            return $item;
        }
        return 'not hit';
    }
}
