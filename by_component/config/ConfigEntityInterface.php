<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 18:13
 */

namespace by\component\config;


interface ConfigEntityInterface
{
    public function getType();
    public function getValue();
    public function isEnable();
}
