<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 11:31
 */

namespace App\Controller;


use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\user\enum\UserLogType;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class UserLogController extends BaseSymfonyApiController
{

    /**
     * @var UserLogServiceInterface
     */
    protected $service;

    public function __construct(UserLogServiceInterface $service, KernelInterface $kernel)
    {
        $this->service = $service;
        parent::__construct($kernel);
    }

    public function query($uid, PagingParams $pagingParams, $logType = '') {
        $map = ['uid' => $uid];
        if (UserLogType::isLegal($logType)) {
            $map['log'] = $logType;
        }
        return $this->service->queryBy($map, $pagingParams, ['createTime' => 'desc']);
    }
}
