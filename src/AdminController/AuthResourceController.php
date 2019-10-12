<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 14:53
 */

namespace App\AdminController;


use App\Entity\AuthResource;
use App\ServiceInterface\AuthResourceServiceInterface;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class AuthResourceController extends BaseSymfonyApiController
{
    /**
     * @var AuthResourceServiceInterface
     */
    protected $service;

    public function __construct(AuthResourceServiceInterface $service, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->service = $service;
    }

    /**
     * 创建
     * @param $name
     * @param $note
     * @param string $action
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($name, $action, $note = '') {
        $entity = new AuthResource();
        $entity->setName($name);
        $entity->setAction($action);
        $entity->setNote($note);
        return $this->service->add($entity);
    }

    /**
     * 查询
     * @param PagingParams $pagingParams
     * @param string $name
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $name = '') {
        $map = [];
        if (!empty($name)) {
            $map['name'] = $name;
        }
        return $this->service->queryBy($map, $pagingParams, ["id" => "desc"]);
    }

    /**
     * 更新
     * @param $id
     * @param string|bool $name
     * @param string|bool $note
     * @param string|bool $action
     * @return mixed|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $name = '', $note = '', $action = '') {
        $info = $this->service->info(['id' => $id]);
        if ($info instanceof AuthResource) {
            if (!empty($note)) {
                $info->setNote($note);
            }

            if (!empty($name)) {
                $info->setName($name);
            }

            if (!empty($action)) {
                $info->setAction($action);
            }
            $this->service->flush($info);
            return $info;
        }
        return "record not exist";
    }
}
