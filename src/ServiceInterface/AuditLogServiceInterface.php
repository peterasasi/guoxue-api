<?php


namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;

interface AuditLogServiceInterface extends BaseServiceInterface
{
    public function log($content, $auditUid, $auditNick = '', $objectId = 0, $objectType = '');
}
