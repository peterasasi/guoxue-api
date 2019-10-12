<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Helper;


use by\component\string_extend\helper\StringHelper;

class CodeGenerator
{
    /**
     * 长度取决uid的大小,  至少大于24
     * @param $uid
     * @param int $itemId
     * @return string
     */
    public static function orderCode($uid, $itemId = 0) {
        $md5Item = substr(md5($itemId), 0, 6);
        // 24+ 长度
        return strtoupper(date("YmdHis").StringHelper::intTo62($uid).StringHelper::randNumbers(4).$md5Item);
    }

    public static function payCode($uid, $orderId = 0) {
        $md5Item = substr(md5($orderId), 0, 6);
        // 24+ 长度
        return strtoupper(date("YmdHis").StringHelper::intTo62($uid).StringHelper::randNumbers(4).$md5Item);
    }

    public static function payCodeByClientId($clientId) {
        $mt = explode(" ", microtime());
        $time = str_pad(intval($mt[0] * 1000000), 6, "0", STR_PAD_RIGHT).$mt[1];

        $md5 = substr(md5($clientId), 0, 8);

        // 16 + 8 + 6 = 30位长度
        return strtoupper($time.$md5.StringHelper::randNumbers(6));
    }

    public static function goodsSkuNo($cateId, $goodsId, $postFix) {
        $key = 'A';
        $key.= str_pad(self::intTo35Hex($goodsId), 5, "0", STR_PAD_LEFT);
        $key .= str_pad(self::intTo35Hex($postFix), 3, '0', STR_PAD_LEFT);
        return $key;
    }



    public static function char35ToInt($c35)
    {
        $len = strlen($c35);
        if ($len > 10) return -1;
        $num = 0;
        $cnt = 0;
        while ($cnt < $len) {
            $index = 0;
            for ($i = 0; $i < 35; $i++) {
                if (self::$char35[$i] == substr($c35, $cnt, 1)) {
                    $index = $i;
                    break;
                }
            }
            $num = $num + ($index + 1) * self::$pow35[$len - $cnt - 1];
            $cnt++;
        }
        return $num;
    }
    public static $pow35 = [
        1,
        35,
        1225,
        42875,
        1500625,
        52521875
    ];
    public static $char35 = [
        "1", "2", "3", "4", "5", "6", "7", "8", "9", 'A',
        'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
        'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z'
    ];

    public static function intTo35Hex($num)
    {
        $num = intval($num);
        if ($num <= 0)
            return 0;
        $char = '';
        do {
            $key = $num % 35;
            $char = self::$char35[$key] . $char;
            $num = floor(($num - $key) / 35);
            if (strlen($char) > 10) return -1;
        } while ($num > 0);
        return $char;
    }

}
