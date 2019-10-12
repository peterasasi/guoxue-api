<?php

namespace by\component\user\logic;


use by\component\tp5\logic\BaseLogic;
use by\component\user\entity\UserLogEntity;

interface  UserLogInterface
{
    /**
     * 用户登录日志记录
     * @param $uid
     * @param $ip
     * @param $deviceType
     * @param string $ua
     * @return bool|int
     */
    public function login($uid, $ip, $deviceType, $ua = '');
}
