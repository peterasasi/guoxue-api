<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\ServiceInterface;


use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use Dbh\SfCoreBundle\Common\BaseServiceInterface;
interface MessageBoxServiceInterface extends BaseServiceInterface
{

    /**
     * 查询明确发给uid的消息
     * @param integer $uid 接收人uid
     * @param string $projectId 项目
     * @param string $type 【数据字典-code】 消息类型
     * @param PagingParams $pagingParams
     * @param bool $readStatus
     * @return
     */
    public function receiveUserMessage($uid, $projectId, $type, PagingParams $pagingParams, $readStatus = false);

    /**
     * 查询所有
     * @param $uid
     * @param $projectId
     * @param $type
     * @param PagingParams $pagingParams
     * @param bool $readStatus
     */
    public function receiveAllMessage($uid, $projectId, $type, PagingParams $pagingParams, $readStatus = false);

    /**
     * 查询只发给全部用户的消息
     * @param $projectId
     * @param $type
     * @param PagingParams $pagingParams
     * @param bool $readStatus
     * @return CallResult
     */
    public function receivePublicMessage($projectId, $type, PagingParams $pagingParams, $readStatus = false): CallResult;

    /**
     * 已读 - 单条消息已读
     * @param $uid
     * @param $msgId
     */
    public function read($uid, $msgId);
}
