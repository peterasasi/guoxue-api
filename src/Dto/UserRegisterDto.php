<?php


namespace App\Dto;

use by\infrastructure\base\BaseObject;
use by\infrastructure\helper\Object2DataArrayHelper;
use by\infrastructure\interfaces\ObjectToArrayInterface;

/**
 * Class UserInfoEntity
 * 用户注册信息
 */
class UserRegisterDto extends BaseObject implements ObjectToArrayInterface
{
    private $mobile;
    private $countryNo;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();
    }

    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getCountryNo()
    {
        return $this->countryNo;
    }

    /**
     * @param mixed $countryNo
     */
    public function setCountryNo($countryNo): void
    {
        $this->countryNo = $countryNo;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

}
