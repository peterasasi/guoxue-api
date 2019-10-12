<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\SpBrand;
use App\Entity\SpCate;
use App\Entity\SpProperty;
use App\Entity\SpPropertyValue;
use App\ServiceInterface\SpBrandServiceInterface;
use App\ServiceInterface\SpCateServiceInterface;
use App\ServiceInterface\SpPropertyServiceInterface;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpCateController extends BaseSymfonyApiController
{
    protected $spCateService;
    protected $spPropertyService;
    protected $brandService;

    public function __construct(SpBrandServiceInterface $brandService, SpPropertyServiceInterface $spPropertyService, SpCateServiceInterface $spCateService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->brandService = $brandService;
        $this->spCateService = $spCateService;
        $this->spPropertyService = $spPropertyService;
    }

    public function info($id)
    {

        $ret = [
            'parent_id' => -1,
            'id' => -1,
            'title' => '',
        ];
        if ($id > 0) {
            $spCate = $this->spCateService->findById($id);
            if ($spCate instanceof SpCate) {
                $ret = [
                    'parent_id' => $spCate->getParentId(),
                    'id' => $spCate->getId(),
                    'title' => $spCate->getTitle(),
                ];
            }
        }
        return CallResultHelper::success($ret);
    }

    /**
     * 创建类目
     * @param $title
     * @param int $leaf
     * @param int $sort
     * @param int $parentId
     * @return \by\infrastructure\base\CallResult
     */
    public function create($title, $leaf = 0, $sort = 0, $parentId = 0)
    {
        $entity = new SpCate();
        $entity->setTitle($title);
        $entity->setLeaf($leaf);
        $entity->setLevel(0);
        $entity->setSort($sort);
        $entity->setParentId(0);
        $entity->setStatus(StatusEnum::ENABLE);
        if (intval($parentId) > 0) {
            $parentCate = $this->spCateService->findById($parentId);
            if ($parentCate instanceof SpCate) {
                if ($parentCate->getLevel() >= 3) {
                    return 'cant add sub category';
                }
                if ($parentCate->getLevel() == 2) {
                    $entity->setLeaf(1);
                }
                $entity->setLevel($parentCate->getLevel() + 1);
                $entity->setParentId($parentCate->getId());
            }
        }
        $this->spCateService->add($entity);
        return CallResultHelper::success($entity->getId());
    }

    /**
     * @param $id
     * @param $title
     * @param int $leaf
     * @param int $sort
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $leaf = 0, $sort = 0)
    {
        $cate = $this->spCateService->findById($id);
        if (!($cate instanceof SpCate)) {
            return 'invalid id';
        }
        $cate->setTitle($title);
        if ($cate->getSort() != intval($sort)) {
            $cate->setSort($sort);
        }
        $cate->setLeaf($leaf);
        $this->spCateService->flush($cate);
        return CallResultHelper::success($cate->getId());
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $cate = $this->spCateService->findById($id);
        if (!($cate instanceof SpCate)) {
            return 'invalid id';
        }
        $cate->setStatus(StatusEnum::SOFT_DELETE);
        $this->spCateService->flush($cate);
        return CallResultHelper::success();
    }

    public function query3Level() {
        $map = ['status' => StatusEnum::ENABLE, 'level' => 0];
        $maxLevel = 3;
        $level = 0;
        $allList = [];
        while ($level < $maxLevel) {
            $map['level'] = $level;
            $allList[$level] = $this->spCateService->queryAllBy($map, ['sort' => "desc"], ["id", "parentId", "title", "leaf", "level"]);
            $level++;
        }
        $level = $maxLevel;
        while ($level - 2 >= 0) {
            foreach ($allList[$level-2] as &$level2) {
                $level2['children'] = [];
                foreach ($allList[$level - 1] as $level3) {
                    if ($level3['parent_id'] == $level2['id']) {
                        array_push($level2['children'], $level3);
                    }
                }
            }
            $level--;
        }

        return CallResultHelper::success($allList[0]);
    }

    /**
     * @param int $parentId
     * @param string $title
     * @return mixed
     */
    public function query($parentId = 0, $title = '')
    {
        $map = ['status' => StatusEnum::ENABLE, 'parent_id' => $parentId];
        if (!empty($title)) {
            $map['title'] = ['like', '%' . $title . '%'];
        }
        return $this->spCateService->queryAllBy($map, ['id' => 'asc', 'sort' => 'desc']);
    }

    public function removeProp($cateId, $propId) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }
        $spProperty = $this->spPropertyService->findById($propId);
        if (!($spProperty instanceof SpProperty)) {
            return 'invalid id';
        }
        $spProperty->removeCate($spCate);
//        $spCate->removeSpProperty($spProperty);
//        $this->spCateService->flush($spCate);
        $this->spPropertyService->flush($spProperty);
        return CallResultHelper::success();
    }

    public function relateProp($cateId, $propId) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }
        $spProperty = $this->spPropertyService->findById($propId);
        if (!($spProperty instanceof SpProperty)) {
            return 'invalid id';
        }

        $spProperty->addCate($spCate);
//        $spCate->addSpProperty($spProperty);
//        $this->spCateService->flush($spCate);
        $this->spPropertyService->flush($spProperty);
        return CallResultHelper::success();
    }

    public function getProp($cateId, $isSale = 0) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }
        $props = $spCate->getSpProperties();
        if (intval($isSale) === 0) {
            $props = $props->filter(function (SpProperty $spProperty) {
                return empty($spProperty->getIsSale());
            });
        } elseif (intval($isSale) == 1) {
            $props = $props->filter(function (SpProperty $spProperty) {
                return $spProperty->getIsSale();
            });
        }

        $props = $props->map(function ($prop) {
            if (!($prop instanceof SpProperty)) return [];
            return [
                'id' => $prop->getId(),
                'title' => $prop->getTitle(),
                'is_color' => $prop->getIsColor(),
                'is_sale' => $prop->getIsSale(),
                'prop_values' => $prop->getSpPropertyValues()->map(function ($value) {
                    if (!($value instanceof SpPropertyValue)) return [];
                    return [
                        'id' => $value->getId(),
                        'title' => $value->getTitle()
                    ];
                }),
                'prop_type' => $prop->getPropType(),
            ];
        });
        return array_values($props->toArray());
    }

    public function relateBrand($cateId, $brandId) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }
        $brand = $this->brandService->findById($brandId);
        if (!($brand instanceof SpBrand)) {
            return 'invalid id';
        }

        $brand->addCate($spCate);
        $this->brandService->flush($brand);
        return CallResultHelper::success();
    }

    public function removeBrand($cateId, $brandId) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }
        $brand = $this->brandService->findById($brandId);
        if (!($brand instanceof SpBrand)) {
            return 'invalid id';
        }

        $brand->removeCate($spCate);
//        $spCate->removeSpProperty($spProperty);
//        $this->spCateService->flush($spCate);
        $this->brandService->flush($brand);
        return CallResultHelper::success();
    }

    public function getBrand($cateId) {
        $spCate = $this->spCateService->findById($cateId);
        if (!($spCate instanceof SpCate)) {
            return 'invalid id';
        }

        return $spCate->getSpBrands()->map(function ($vo) {
            if (!($vo instanceof SpBrand)) return [];
            return [
                'id' => $vo->getId(),
                'title' => $vo->getTitle(),
                'description' => $vo->getDescription(),
                'icon' => $vo->getIcon(),
            ];
        });
    }


}
