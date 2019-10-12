<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/9
 * Time: 14:55
 */

namespace App\Entity;


abstract class BaseEntity
{
    protected $_entity_version = 1;

    public function __construct()
    {
        if (method_exists($this, "setCreateTime")) {
            $this->setCreateTime(0);
        }

        if (method_exists($this, "setUpdateTime")) {
            $this->setUpdateTime(0);
        }
    }

    /**
     * @return int
     */
    public function getEntityVersion(): int
    {
        return $this->_entity_version;
    }
}
