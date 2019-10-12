<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\Entity\Album;
use App\Entity\AlbumCategory;
use App\ServiceInterface\AlbumCategoryServiceInterface;
use App\ServiceInterface\AlbumServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class AlbumCategoryController extends BaseSymfonyApiController
{
    /**
     * @var AlbumCategoryServiceInterface
     */
    protected $albumCategoryService;

    /**
     * @var AlbumServiceInterface
     */
    protected $albumService;

    public function __construct(AlbumServiceInterface $albumService, AlbumCategoryServiceInterface $albumCategoryService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->albumCategoryService = $albumCategoryService;
        $this->albumService = $albumService;
    }

    public function all() {
        return $this->albumCategoryService->queryAllBy([]);
    }

    public function query(PagingParams $pagingParams) {
        return $this->albumCategoryService->queryAndCount([], $pagingParams);
    }

    /**
     * @param $title
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($title) {
        $albumCategory = new AlbumCategory();
        $albumCategory->setTitle($title);
        return $this->albumCategoryService->add($albumCategory);
    }

    public function update($id, $title) {
        $albumCategory = $this->albumCategoryService->info(['id' => $id]);
        if (!($albumCategory instanceof AlbumCategory)) {
            return 'record not exists';
        }
        if ($albumCategory->getTitle() != $title) {
            $albumCategory->setTitle($title);
            $this->albumCategoryService->flush($albumCategory);
        }
        return CallResultHelper::success();
    }

    public function delete($id) {
        $album = $this->albumService->info(['cate_id' => $id]);
        if ($album instanceof Album) {
            return 'delete deny';
        }
        $albumCategory = $this->albumCategoryService->info(['id' => $id]);

        if (!($albumCategory instanceof AlbumCategory)) {
            return 'record not exists';
        }
        $this->albumCategoryService->delete($albumCategory);
        return CallResultHelper::success();
    }
}
