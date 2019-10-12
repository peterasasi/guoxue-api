<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/15
 * Time: 14:28
 */

namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface ConfigServiceInterface extends BaseServiceInterface
{
    /**
     * 根据projectId 生成默认的配置
     * @param $projectId
     * @return mixed
     */
    public function initByProjectId($projectId);
}
