<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Helper;


class MarkdownHelper
{
    public static function getImgUrlFromMarkdown($md, $cnt = 0)
    {
        $reg = '/!\[(.*?)\]\((.*?)\)/';
        preg_match_all($reg, $md, $match);
        $alt = $match[1];
        $imgUrl = $match[2];
        $parse = [];
        for ($i = 0; $i < count($alt) && $i < count($imgUrl); $i++) {

            if (empty($imgUrl[$i])) continue;

            if ($cnt > 0 && count($parse) >= $cnt) return $parse;

            array_push($parse, [
                'alt' => $alt[$i],
                'img' => $imgUrl[$i]
            ]);
        }
        return $parse;
    }
}