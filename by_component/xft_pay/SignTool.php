<?php


namespace by\component\xft_pay;


class SignTool
{
    public static function sign($body, XftPayConfig $config) {
        $filterBody = [];
        foreach ($body as $key => $value) {
            if (strlen($value) > 0) {
                $filterBody[$key] = $value;
            }
        }
        ksort($filterBody, SORT_ASC);

        return strtoupper(md5(urldecode(http_build_query($filterBody)).'&key='.$config->getKey()));
    }
}
