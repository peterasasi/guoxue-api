<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGrade
 *
 * @ORM\Table(name="user_grade")
 * @ORM\Entity
 */
class UserGrade extends BaseEntity
{
    const Normal = 1;

    const VIP1 = 2;


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
     * @ORM\Column(name="grade_id", type="bigint", nullable=false, options={"comment"="会员等级id"})
     */
    private $gradeId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="bigint", nullable=false)
     */
    private $status = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="create_time", type="bigint", nullable=false, options={"comment"="升级时间"})
     */
    private $createTime = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="update_time", type="bigint", nullable=false)
     */
    private $updateTime = '0';

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
    public function getGradeId(): int
    {
        return $this->gradeId;
    }

    /**
     * @param int $gradeId
     */
    public function setGradeId(int $gradeId): void
    {
        $this->gradeId = $gradeId;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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

}
