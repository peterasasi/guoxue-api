<?php


namespace App\Helper;


use by\component\encrypt\des\Des;
use Dbh\SfCoreBundle\Common\ByEnv;

class DesHelper
{
    /**
     * @param $content
     */
    public static function encode($content)
    {
        return Des::encode(base64_decode($content), ByEnv::get('DES_SECRET'));
    }

    public static function decode($content)
    {
        return base64_encode(Des::decode($content, ByEnv::get('DES_SECRET')));
    }
}
