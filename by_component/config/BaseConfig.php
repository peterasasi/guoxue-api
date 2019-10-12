<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 11:01
 */

namespace by\component\config;


use by\component\config\constants\SysConfigTypeEnum;

class BaseConfig
{

    /**
     * @var ConfigStorageInterface
     */
    protected $configStorage;

    /**
     * 该配置需要的配置键
     * @var array
     */
    protected $keys;

    /**
     * 配置键值前缀
     * @var string
     */
    protected $prefix;

    public function __construct(ConfigStorageInterface $configStorage, array $keys = [])
    {
        $this->configStorage = $configStorage;
        $this->keys = $keys;
        $this->prefix = strtolower(str_replace("\\", ".", get_class($this)));
    }

    function set($key, $value, $type)
    {
        if (!$this->isSupportKeys($key)) {
            throw new \InvalidArgumentException($key.'is not support by this config');
        }
        if (!$this->isSupportType($type)) {
            throw new \InvalidArgumentException($type.' is not a valid config type.');
        }

        $this->configStorage->set($key, $value, $type);
    }

    public function isSupportKeys($key):bool
    {
        return in_array($key, $this->keys);
    }

    public function isSupportType($type): bool  {
        return SysConfigTypeEnum::isValid($type);
    }

    function get($key)
    {
        if (!$this->isSupportKeys($key)) {
            throw new \InvalidArgumentException($key);
        }
        return $this->configStorage->get($key);
    }

    function getAll()
    {
        return $this->configStorage->getAll();
    }

}
