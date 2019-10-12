<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 17:49
 */

namespace App\Tests;

use by\component\string_extend\helper\StringHelper;
use PHPUnit\Framework\TestCase;

class ArrayKeyTest extends TestCase
{
    public function testConvert() {

        $arr = [
            'update_time' => '2323',
            'create_time' => '34',
        ];
        $list = [];
        foreach ($arr as $key => $vo) {
            $newKey = StringHelper::toCamelCase($key);
            $list[$newKey] = $vo;
        }
        var_dump($list);
        $this->assertEquals(2, count($list));
        $this->assertEquals($list['updateTime'], $arr['update_time']);
    }
}
