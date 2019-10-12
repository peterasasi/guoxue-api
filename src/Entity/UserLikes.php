<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLikes
 *
 * @ORM\Table(name="user_likes")
 * @ORM\Entity
 */
class UserLikes
{
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"comment"="用户id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $uid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="likes_key", type="string", length=64, nullable=false, options={"comment"="喜好项目的唯一键"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $likesKey = '';

    /**
     * @var string
     *
     * @ORM\Column(name="likes_item_type", type="string", length=32, nullable=false, options={"fixed"=true,"comment"="喜好项目的类型"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $likesItemType = '';

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


}
