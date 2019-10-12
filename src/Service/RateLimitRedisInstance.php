<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/21
 * Time: 11:06
 */

namespace App\Service;

use Redis;

class RateLimitRedisInstance extends Redis
{
    public function __construct()
    {
        $this->connect("127.0.0.1", "6379", 10);
    }
}
