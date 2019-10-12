<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/22
 * Time: 16:29
 */

namespace App\AdminController;


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
     * 删除
     * @param $parentId
     */
//    public function clear($parentId) {
//        return $this->service->deleteWhere(['parent_id' => $parentId]);
//    }

    /**
     * @param $id
     * @param bool $name
     * @param bool $alias
     * @param int $sort
     * @param bool $notes
     * @param int $icon
     * @param int $dataLevel
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $name = false, $alias = false, $sort = -1, $notes = false, $icon = -1, $dataLevel = -1) {
        $update = [];
        if ($name !== false) $update['name'] = $name;
        if ($alias !== false) $update['alias'] = $alias;
        if ($sort !== -1) $update['sort'] = $sort;
        if ($notes !== false) $update['notes'] = $notes;
        if ($icon !== -1) $update['icon'] = $icon;
        if ($dataLevel !== -1) $update['data_level'] = $dataLevel;

        return $this->service->updateOne(['id' => $id], $update);
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

    /**
     * 创建数据字典
     * @param $name
     * @param string $alias
     * @param int $sort
     * @param string $icon
     * @param string $notes
     * @param int $parentId
     * @param int $dataLevel
     * @return mixed
     * @throws UglyException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($name, $alias = '', $sort = 0, $icon = '', $notes = '', $parentId = 0, $dataLevel = 1)
    {
        $parentId = intval($parentId);
        if ($parentId  > 0) {
            $parent = $this->service->info(['id' => $parentId]);
        } else {
            $parent = new Datatree();
            $parent->setCode('');
            $parent->setParents('');
            $parent->setLevel(0);
        }
        $entity = new Datatree();
        $entity->setName($name);
        $entity->setAlias($alias);
        $entity->setSort($sort);
        $entity->setIcon($icon);
        $entity->setNotes($notes);
        $entity->setParentId($parentId);
        $entity->setDataLevel($dataLevel);
        $entity->setCode($this->getNextCode($parent));
        $entity->setParents(rtrim($parent->getParents(), ',').','.$parentId.',');
        $entity->setLevel($parent->getLevel() + 1);

        return $this->service->add($entity);
    }


    protected function getNextCode(Datatree $parent)
    {

        $result =$this->service->info(['code' => ['like', $parent->getCode() . '___']], ["code" => "desc"]);
        $code = $parent->getCode() . '001';

        if (empty($result)) {
            return $code;
        }
        if ($result instanceof Datatree) {
            $parent_code = $result->getCode();
        } else {
            $parent_code = $result['code'];
        }

        $hex36 = substr($parent_code, strlen($parent_code) - 3, 3);
        $num = StringHelper::char36ToInt($hex36) + 1;
        $hex36 = StringHelper::intTo36Hex($num);
        if (strlen($hex36) < 3) {
            $hex36 = str_pad($hex36, 3, "0", STR_PAD_LEFT);
        }
        $code = substr($parent_code, 0, strlen($parent_code) - 3) . $hex36;
        return $code;
    }

    public function delete($id) {
        return 'delete deny';
    }
}
