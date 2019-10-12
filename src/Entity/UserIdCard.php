<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserIdCard
 *
 * @ORM\Table(name="user_id_card", uniqueConstraints={@ORM\UniqueConstraint(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class UserIdCard extends BaseEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false)
     */
    private $uid = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="front_img", type="string", length=256, nullable=false)
     */
    private $frontImg = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="back_img", type="string", length=256, nullable=false)
     */
    private $backImg = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="card_no", type="string", length=32, nullable=false)
     */
    private $cardNo = '';

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=32, nullable=false)
     */
    private $realName = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="sex", type="boolean", nullable=false)
     */
    private $sex = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="birthday", type="bigint", nullable=false, options={"comment"="生日"})
     */
    private $birthday = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="create_time", type="bigint", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="update_time", type="bigint", nullable=false)
     */
    private $updateTime = '0';

    /**
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $handHoldImg;

    /**
     * @ORM\Column(type="integer")
     */
    private $verify;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $backImgId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $frontImgId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $handHoldImgId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $expireDate;

    public function __construct()
    {
        parent::__construct();
        $this->setBackImgId(0);
        $this->setFrontImgId(0);
        $this->setHandHoldImgId(0);
        $this->setExpireDate('');
        $this->setAddress('');
        $this->setEmail('');
        $this->setZipcode('');
        $this->setVerify(0);
        $this->setHandHoldImg('');
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getFrontImg(): string
    {
        return $this->frontImg;
    }

    /**
     * @param string $frontImg
     */
    public function setFrontImg(string $frontImg): void
    {
        $this->frontImg = $frontImg;
    }

    /**
     * @return int
     */
    public function getBackImg(): string
    {
        return $this->backImg;
    }

    /**
     * @param string $backImg
     */
    public function setBackImg(string $backImg): void
    {
        $this->backImg = $backImg;
    }

    /**
     * @return string
     */
    public function getCardNo(): string
    {
        return $this->cardNo;
    }

    /**
     * @param string $cardNo
     */
    public function setCardNo(string $cardNo): void
    {
        $this->cardNo = $cardNo;
    }

    /**
     * @return string
     */
    public function getRealName(): string
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     */
    public function setRealName(string $realName): void
    {
        $this->realName = $realName;
    }

    /**
     * @return bool
     */
    public function isSex(): bool
    {
        return $this->sex;
    }

    /**
     * @param bool $sex
     */
    public function setSex(bool $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return int
     */
    public function getBirthday(): int
    {
        return $this->birthday;
    }

    /**
     * @param int $birthday
     */
    public function setBirthday(int $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getUpdateTime(): int
    {
        return $this->updateTime;
    }

    /**
     * @param int $updateTime
     */
    public function setUpdateTime(int $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getHandHoldImg(): ?string
    {
        return $this->handHoldImg;
    }

    public function setHandHoldImg(string $handHoldImg): self
    {
        $this->handHoldImg = $handHoldImg;

        return $this;
    }

    public function getVerify(): ?int
    {
        return $this->verify;
    }

    public function setVerify(int $verify): self
    {
        $this->verify = $verify;

        return $this;
    }

    public function getBackImgId(): ?string
    {
        return $this->backImgId;
    }

    public function setBackImgId(string $backImgId): self
    {
        $this->backImgId = $backImgId;

        return $this;
    }

    public function getFrontImgId(): ?string
    {
        return $this->frontImgId;
    }

    public function setFrontImgId(string $frontImgId): self
    {
        $this->frontImgId = $frontImgId;

        return $this;
    }

    public function getHandHoldImgId(): ?string
    {
        return $this->handHoldImgId;
    }

    public function setHandHoldImgId(string $handHoldImgId): self
    {
        $this->handHoldImgId = $handHoldImgId;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpireDate(): ?string
    {
        return $this->expireDate;
    }

    public function setExpireDate(string $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }
}
