<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/22
 * Time: 16:29
 */

namespace App\Controller;


use App\Entity\Datatree;
use App\Exception\UglyException;
use App\ServiceInterface\DatatreeServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class DatatreeController extends BaseSymfonyApiController
{
    /**
     * @var DatatreeServiceInterface
     */
    protected $service;

    public function __construct(DatatreeServiceInterface $datatreeService, KernelInterface $kernel)
    {
        $this->service = $datatreeService;
        parent::__construct($kernel);
    }

    /**
     * @param PagingParams $pagingParams
     * @param string $name
     * @param int $parentId
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $name = '', $parentId = 0) {
        $map = [
            'parent_id' => $parentId
        ];
        if (strlen($name) > 0) {
            $map['name'] = ['like', '%'.$name.'%'];
        }
        $list = $this->service->queryBy($map, $pagingParams, ['code' => 'asc']);

        unset($map['parent_id']);

        $map['parentId'] = $parentId;

        $count = $this->service->count($map);

        return CallResultHelper::success(['count' => $count, 'list' => $list]);
    }
}
