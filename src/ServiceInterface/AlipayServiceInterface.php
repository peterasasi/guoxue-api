<?php


namespace App\ServiceInterface;


use by\component\proxyPay\Supports\Collection;

interface AlipayServiceInterface
{
    /**
     * @throws \by\component\proxyPay\Exceptions\InvalidConfigException
     * @throws \by\component\proxyPay\Exceptions\InvalidSignException
     * @return mixed
     */
    public function verify();

    public function success();

    public function getConfig($key);

    public function query($payCode): Collection;

    public function refund($refundInfo): Collection;

    public function web($order);

    public function wap($order);
}
