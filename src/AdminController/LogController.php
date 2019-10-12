<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/21
 * Time: 13:52
 */

namespace App\AdminController;


use App\ServiceInterface\ApiReqHisServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class LogController extends BaseSymfonyApiController
{
    /**
     * @var ApiReqHisServiceInterface
     */
    protected $service;

    public function __construct(KernelInterface $kernel, ApiReqHisServiceInterface $service)
    {
        $this->service = $service;
        parent::__construct($kernel);
    }

    /**
     * 查询某一天的调用情况
     * @param $clientId
     * @param PagingParams $pagingParams
     * @param string $order
     * @param string $ymd
     * @return mixed
     */
    public function queryApi($clientId, PagingParams $pagingParams, $order = 'cnt,desc', $ymd = '')
    {
        if (empty($ymd)) {
            $ymd = date("Ymd", time());
        }
        $order = explode(",", $order);
        if (count($order) == 2) {
            $order = [$order[0] => $order[1]];
        } else {
            $order = ['cnt' => 'desc'];
        }

        $list = $this->service->queryBy(['client_id' => $clientId, 'ymd' => $ymd], $pagingParams, $order);
        $count = $this->service->count(['clientId' => $clientId, 'ymd' => $ymd]);

        return CallResultHelper::success([
            'list' => $list,
            'count' => $count
        ]);
    }
}
