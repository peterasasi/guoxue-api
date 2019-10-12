<?php


namespace by\component\audit_log;


class AuditStatus
{
    // 通过
    const Passed = 1;

    // 驳回
    const Denied = -1;

    // 初始状态
    const Initial = 0;
}
