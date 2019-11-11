<?php


namespace App\Helper;


class UserVip
{
    public static function level($level) {
        return $level - 1 < 0 ? 0 : $level - 1;
    }
}
