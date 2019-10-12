<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGradeExp
 *
 * @ORM\Table(name="user_grade_exp")
 * @ORM\Entity
 */
class UserGradeExp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rule", type="string", length=32, nullable=false, options={"fixed"=true})
     */
    private $rule = '';

    /**
     * @var int
     *
     * @ORM\Column(name="group_id", type="bigint", nullable=false, options={"comment"="会员组"})
     */
    private $groupId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="project_id", type="string", length=32, nullable=false)
     */
    private $projectId = '';

    /**
     * @var string
     *
     * @ORM\Column(name="rule_desc", type="string", length=64, nullable=false)
     */
    private $ruleDesc = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean", nullable=false, options={"default"="1"})
     */
    private $enable = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="exp", type="integer", nullable=false, options={"default"="1"})
     */
    private $exp = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="cat_delete", type="boolean", nullable=false, options={"default"="1","comment"="是否可删除，默认可以"})
     */
    private $catDelete = '1';

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
