<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Common;


class CacheKeys
{

    // 接口短信配置信息
    const SmsConfig = 'api_sms_config';

    // app 首页融合接口
    const AppCombIndex = 'app_comb_index';

    const AppAlipayConfig = 'app_ali_pay_config';

    public static $ExpireTimes = [
        CacheKeys::SmsConfig => 3600,
        CacheKeys::AppCombIndex => 300,
        CacheKeys::AppAlipayConfig => 24 * 3600
    ];

    public static function getExpireTime($key)
    {
        if (array_key_exists($key, self::$ExpireTimes)) {
            return self::$ExpireTimes[$key];
        } else {
            return 30;
        }
    }
}
