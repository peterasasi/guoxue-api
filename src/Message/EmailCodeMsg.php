<?php


namespace App\Message;


/**
 * Class EmailCodeMsg
 * 邮件验证码
 * @package App\Message
 */
class EmailCodeMsg
{
    protected $projectId;
    protected $toEmail;
    protected $code;

    /**
     * EmailCodeMsg constructor.
     * @param $projectId
     * @param $toEmail
     * @param $code
     */
    public function __construct($projectId, $toEmail, $code)
    {
        $this->projectId = $projectId;
        $this->toEmail = $toEmail;
        $this->code = $code;
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
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @param mixed $toEmail
     */
    public function setToEmail($toEmail): void
    {
        $this->toEmail = $toEmail;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }
}
