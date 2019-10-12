<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\SpProperty;
use App\Entity\SpPropertyValue;
use App\ServiceInterface\SpPropertyServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpPropertyController extends BaseSymfonyApiController
{
    protected $propService;

    public function __construct(SpPropertyServiceInterface $propertyService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->propService = $propertyService;
    }

    public function info($id) {
        $prop = $this->propService->findById($id);

        if (!($prop instanceof SpProperty)) {
            return 'invalid id';
        }
        return CallResultHelper::success([
            'id' => $prop->getId(),
            'title' => $prop->getTitle(),
            'prop_type' => $prop->getPropType(),
            'is_sale' => $prop->getIsSale(),
            'is_color' => $prop->getIsColor(),
            'prop_values' => $prop->getSpPropertyValues()->map(function ($value) {
                if (!($value instanceof SpPropertyValue)) return [];
                return [
                    'id' => $value->getId(),
                    'title' => $value->getTitle()
                ];
            })
        ]);
    }

    public function query($title, PagingParams $pagingParams) {
        $map = [];
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title.'%'];
        }
        $list = $this->propService->queryAndCount($map, $pagingParams, ['id' => 'desc']);
        return $list;
    }

    public function create($title, $isColor = 0, $isSale = 0, $propType = SpProperty::SingleProperty) {
        $prop = new SpProperty();
        $prop->setTitle($title);
        $prop->setIsColor($isColor ? true : false);
        $prop->setIsSale($isSale ? true : false);
        $prop->setPropType($propType);
        $this->propService->add($prop);
        return CallResultHelper::success($prop->getId());
    }


    public function update($id, $title, $isColor = 0, $isSale = 0, $propType = SpProperty::SingleProperty) {
        $prop = $this->propService->findById($id);
        if (!($prop instanceof SpProperty)) {
            return 'invalid id';
        }
        $prop->setTitle($title);
        $prop->setIsColor($isColor ? true : false);
        $prop->setIsSale($isSale ? true : false);
        $prop->setPropType($propType);
        $this->propService->flush($prop);
        return CallResultHelper::success($prop->getId());
    }

    public function delete($id) {
        $prop = $this->propService->findById($id);
        if (!($prop instanceof SpProperty)) {
            return 'invalid id';
        }

        if ($prop->getCate()->count() > 0) {
            return 'attribute in use';
        }

        if ($prop->getSpPropertyValues()->count() > 0) {
            return 'attribute has values';
        }

        $this->propService->delete($prop);
        return CallResultHelper::success();
    }
}
