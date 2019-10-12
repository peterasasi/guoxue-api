<?php

namespace by\component\user\helper;


class PasswordHelper
{
    public static function md5Sha1String($str, $salt = '123456')
    {
        return '' === $str ? '' : md5(sha1($str) . $salt);
    }

}
