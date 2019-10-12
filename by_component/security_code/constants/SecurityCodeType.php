<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 17:06
 */

namespace by\component\security_code\constants;


class SecurityCodeType
{

    /**
     * 注册
     */
    const TYPE_FOR_REGISTER = 1;

    /**
     * 更新密码
     */
    const TYPE_FOR_UPDATE_PSW = 2;

    /**
     * 绑定手机号,之前未绑定过
     */
    const TYPE_FOR_NEW_BIND_PHONE = 3;

    /**
     * 更换手机号,
     */
    const TYPE_FOR_CHANGE_NEW_PHONE = 4;

    /**
     * 用于登录
     */
    const TYPE_FOR_LOGIN = 5;
    /**
     * 找回密码
     */
    const TYPE_FOR_FOUND_PSW = 6;
    /**
     * 验证是否本人操作
     */
    const TYPE_FOR_Auth_Self = 7;
    /**
     * 用户安全验证码
     * 比如: 发送短信时要验证下 图片验证码，避免被机器人频繁滥用
     */
    const TYPE_FOR_Safe_Code = 8;

    public static function getTypeDesc($type)
    {
        switch ($type) {
            case SecurityCodeType::TYPE_FOR_CHANGE_NEW_PHONE:
                return ('change new phone');
            case SecurityCodeType::TYPE_FOR_NEW_BIND_PHONE:
                return ('bind new phone');
            case SecurityCodeType::TYPE_FOR_REGISTER:
                return ('register');
            case SecurityCodeType::TYPE_FOR_UPDATE_PSW:
                return ('update password');
            case SecurityCodeType::TYPE_FOR_LOGIN:
                return ('login');
            case SecurityCodeType::TYPE_FOR_FOUND_PSW:
                return ('found password');
            case SecurityCodeType::TYPE_FOR_Auth_Self:
                return ('auth self');
            default:
                return false;
        }
    }
}
