<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class UserRegisterEvent  extends Event
{
    public const NAME = "user_register";

    protected $uid;
    protected $nickname;
    protected $email;
    protected $mobile;
    protected $countryNo;
    protected $projectId;
    protected $inviteUid;


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
        $this->inviteUid = 0;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getCountryNo(): string
    {
        return $this->countryNo;
    }

    /**
     * @param string $countryNo
     */
    public function setCountryNo(string $countryNo): void
    {
        $this->countryNo = $countryNo;
    }

    /**
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * @param string $projectId
     */
    public function setProjectId(string $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return int
     */
    public function getInviteUid(): int
    {
        return $this->inviteUid;
    }

    /**
     * @param int $inviteUid
     */
    public function setInviteUid(int $inviteUid): void
    {
        $this->inviteUid = $inviteUid;
    }
}
