<?php
/**
 * Copyright (c) 2016.  hangzhou BOYE .Co.Ltd. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-12-13
 * Time: 16:14
 */

namespace by\component\user\enum;

/**
 * 登录设备类型
 * Class LoginDeviceType
 * @package by\component\user\enum
 */
class LoginDeviceType
{
    /**
     * 安卓
     */
    const ANDROID = "android";

    /**
     * ios
     */
    const IPHONE = "iphone";

    /**
     * pc
     */
    const PC = "pc";

    /**
     * 手机版网页
     */
    const MOBILE_WEB = "mobile_web";

    /**
     * 微信公众号
     */
    const WEI_XIN = "wei_xin";

    /**
     * 微信小程序
     */
    const WXAPP = "wxapp";

    /**
     * 未知
     */
    const unknown = "unknown";
}