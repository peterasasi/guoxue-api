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
use App\ServiceInterface\SpPropertyValueServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpPropertyValueController extends BaseSymfonyApiController
{
    protected $propService;
    protected $propValueService;

    public function __construct(SpPropertyServiceInterface $propertyService, SpPropertyValueServiceInterface $propertyValueService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->propValueService = $propertyValueService;
        $this->propService = $propertyService;
    }

    public function query($propId, PagingParams $pagingParams) {
        return $this->propValueService->queryAndCount(['prop' => $propId], $pagingParams, ['id' => 'desc']);
    }

    public function delete($id) {
        $value = $this->propValueService->findById($id);
        if ($value instanceof SpPropertyValue) {
            $this->propValueService->delete($value);
        }
        return CallResultHelper::success();
    }

    public function create($title, $propId) {
        $prop = $this->propService->findById($propId);
        if (!($prop instanceof SpProperty)) return 'invalid id';
        $propValue = new SpPropertyValue();
        $propValue->setTitle($title);
        $propValue->setProp($prop);
        $this->propValueService->add($propValue);
        return CallResultHelper::success($propValue->getId());
    }

    public function update($id, $title) {
        $propValue = $this->propValueService->findById($id);
        if (!($propValue instanceof SpPropertyValue)) {
            return 'invalid id';
        }
        $propValue->setTitle($title);
        $this->propValueService->flush($propValue);
        return CallResultHelper::success($propValue->getId());
    }
}
