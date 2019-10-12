<?php

namespace by\component\user\enum;


class UserLogType
{
    /**
     * 登录日志
     */
    const LOGIN = 1;

    /**
     * 经验值变动
     */
    const EXP = 2;

    /**
     * 管理应用
     */
    const Clients = 3;

    /**
     * 操作日志
     */
    const Operation = 4;

    public static function isLegal($type) {
        if (intval($type) <= 4 && intval($type) >= 1) {
            return true;
        }
        return false;
    }
}
