<?php


namespace by\component\xft_pay;


class SignTool
{
    public static function sign($body, XftPayConfig $config) {
        ksort($body, SORT_ASC);
        return strtoupper(md5(http_build_query($body).'&key='.$config->getKey()));
    }
}
