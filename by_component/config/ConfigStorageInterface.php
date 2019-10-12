<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 10:20
 */

namespace by\component\config;


interface ConfigStorageInterface
{
    function set($key, $value, $type);
    function get($key);
    function getAll($prefix = '');
}
