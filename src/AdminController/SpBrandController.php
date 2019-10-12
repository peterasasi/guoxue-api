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
use App\ServiceInterface\SpBrandServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class SpBrandController extends BaseSymfonyApiController
{
    protected $brandService;

    public function __construct(SpBrandServiceInterface $brandService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->brandService = $brandService;
    }

    public function create($title, $description = '', $icon = '') {
        $brand = new SpBrand();
        $brand->setTitle($title);
        $brand->setDescription($description);
        $brand->setIcon($icon);
        $brand->setStatus(StatusEnum::ENABLE);
        $this->brandService->add($brand);
        return CallResultHelper::success($brand->getId());
    }


    public function update($id, $title, $description = '', $icon = '') {
        $entity = $this->brandService->findById($id);
        if (!($entity instanceof SpBrand)) {
            return 'invalid id';
        }
        $entity->setTitle($title);
        $entity->setDescription($description);
        $entity->setIcon($icon);
        $this->brandService->flush($entity);
        return CallResultHelper::success($entity->getId());
    }

    public function query($title, PagingParams $pagingParams) {
        $map = [
            'status' => StatusEnum::ENABLE
        ];
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title.'%'];
        }
        return $this->brandService->queryAndCount($map, $pagingParams, ['id' => 'desc']);
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $brand = $this->brandService->findById($id);
        if (!($brand instanceof SpBrand)) {
            return 'invalid id';
        }

        if ($brand->getCate()->count() > 0) {
            return 'brand in use';
        }
        $brand->setStatus(StatusEnum::SOFT_DELETE);
        $this->brandService->flush($brand);
        return CallResultHelper::success();
    }
}
