<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserTags
 *
 * @ORM\Table(name="user_tags")
 * @ORM\Entity
 */
class UserTags extends BaseEntity
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
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"comment"="被贴标签的用户id"})
     */
    private $uid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="tag_name", type="string", length=128, nullable=false, options={"comment"="同一个人不能有多个标签"})
     */
    private $tagName = '';

    /**
     * @var int
     *
     * @ORM\Column(name="who_tag_uid", type="bigint", nullable=false, options={"comment"="贴标签用户id(0:系统)"})
     */
    private $whoTagUid = '0';

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * @return int
     */
    public function getWhoTagUid()
    {
        return $this->whoTagUid;
    }

    /**
     * @param int $whoTagUid
     */
    public function setWhoTagUid($whoTagUid)
    {
        $this->whoTagUid = $whoTagUid;
    }

    /**
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param int $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }
}
