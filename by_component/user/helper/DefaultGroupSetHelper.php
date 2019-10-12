<?php

namespace by\component\user\helper;


use by\api\helper\ApiConfigHelper;
use by\component\powersystem\entity\AuthGroupAccessEntity;
use by\component\powersystem\logic\AuthGroupAccessLogic;

class DefaultGroupSetHelper
{

    /**
     * 设置用户的默认用户组
     * @param $projectId
     * @param $uid
     * @param string $defaultGroupId 默认用户组
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setUid($projectId, $uid, $defaultGroupId = '')
    {
        if (empty($defaultGroupId)) {
            $defaultGroupId = ApiConfigHelper::getConfig($projectId, 'sys_default_user_group');
        }
        if (!empty($defaultGroupId) && intval($uid) > 0) {
            $authGroupAccess = new AuthGroupAccessEntity();
            $authGroupAccess->setGroupId($defaultGroupId);
            $authGroupAccess->setUid($uid);
            (new AuthGroupAccessLogic())->add($authGroupAccess);
        }
    }
}
