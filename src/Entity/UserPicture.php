<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPicture
 *
 * @ORM\Table(name="user_picture", uniqueConstraints={@ORM\UniqueConstraint(name="uid", columns={"uid", "pic_id", "scene"})})
 * @ORM\Entity
 */
class UserPicture
{
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $uid;

    /**
     * @var int
     *
     * @ORM\Column(name="pic_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $picId;

    /**
     * @var string
     *
     * @ORM\Column(name="scene", type="string", length=12, nullable=false, options={"default"="none","fixed"=true,"comment"="图片使用场景"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $scene = 'none';

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
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1"})
     */
    private $status = '1';


}
