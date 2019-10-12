<?php


namespace App\Message;


class UserRegisterMsg
{
    protected $uid;
    protected $nickname;
    protected $email;
    protected $mobile;
    protected $countryNo;
    protected $projectId;

    /**
     * UserRegisterMsg constructor.
     * @param $uid
     */
    public function __construct()
    {
        $this->uid = 0;
        $this->nickname = '';
        $this->email = '';
        $this->mobile = '';
        $this->countryNo = '';
        $this->projectId = '';
    }


    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId): void
    {
        $this->projectId = $projectId;
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
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
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

}
