<?php


namespace App\Common;


use by\component\encrypt\des\Des;
use Dbh\SfCoreBundle\Common\ByEnv;

class ByCrypt
{
    public static function desEncode($content) {
        return Des::encode($content, ByEnv::get('APP_SECRET'));
    }

    public static function desDecode($content) {
        return Des::decode($content, ByEnv::get('APP_SECRET'));
    }

    /**
     * 隐藏关键信息
     * @param string $str 原始字符串
     * @param int $firstLen 首部保留字符长度
     * @param int $lastLen  尾部保留字符长度
     * @param string $replaceChar 替换后的字符
     * @param int $replaceCount 替换后字符的数目
     * @return string
     */
    public static function hideSensitive($str, $firstLen = 3, $lastLen = 4, $replaceCount = 4, $replaceChar = '*') {
        if (strlen($str) > $firstLen + $lastLen) {
            return substr($str, 0, $firstLen). str_repeat($replaceChar, $replaceCount).substr($str, -$lastLen);
        }
        return $str;
    }
}
