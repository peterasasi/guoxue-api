<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Common\ByPinyin;
use App\Entity\Album;
use App\Entity\AlbumCategory;
use App\Entity\AlbumModel;
use App\Entity\AlbumPhoto;
use App\Entity\Tags;
use App\ServiceInterface\AlbumCategoryServiceInterface;
use App\ServiceInterface\AlbumModelServiceInterface;
use App\ServiceInterface\AlbumPhotoServiceInterface;
use App\ServiceInterface\AlbumServiceInterface;
use App\ServiceInterface\TagsServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpKernel\KernelInterface;

class AlbumController extends BaseSymfonyApiController
{
    /**
     * @var AlbumServiceInterface
     */
    protected $albumService;
    /**
     * @var TagsServiceInterface
     */
    protected $tagsService;
    /**
     * @var AlbumPhotoServiceInterface
     */
    protected $albumPhotoService;

    /**
     * @var AlbumCategoryServiceInterface
     */
    protected $albumCategoryService;

    /**
     * AlbumModelServiceInterface
     */
    protected $albumModelService;

    public function __construct(AlbumModelServiceInterface $albumModelService, AlbumCategoryServiceInterface $albumCategoryService, AlbumPhotoServiceInterface $albumPhotoService, TagsServiceInterface $tagsService, AlbumServiceInterface $albumService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->albumModelService = $albumModelService;
        $this->albumService = $albumService;
        $this->tagsService = $tagsService;
        $this->albumPhotoService = $albumPhotoService;
        $this->albumCategoryService = $albumCategoryService;
    }

    protected function covertHttps($list) {
        return $list;
    }

    public function queryByModel($modelId, PagingParams $pagingParams) {
        $albumModel = $this->albumModelService->info(['id' => $modelId]);
        if (!($albumModel instanceof AlbumModel)) {
            return CallResultHelper::success([
                'count' => 0,
                'list' => []
            ]);
        }

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('status', StatusEnum::ENABLE));
        $count = $albumModel->getAlbums()->matching($criteria)->count();

        $criteria->orderBy(array("views" => Criteria::DESC))
            ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
            ->setMaxResults($pagingParams->getPageSize());

        $list = $albumModel->getAlbums()->matching($criteria)->toArray();

        return CallResultHelper::success([
            'count' => $count,
            'list' => $list
        ]);
    }

    public function queryByPinyin($pinyin, PagingParams $pagingParams) {
        $tag = $this->tagsService->info(['pinyin' => $pinyin]);
        if (!($tag instanceof Tags)) {
            return CallResultHelper::success([
                'count' => 0,
                'list' => []
            ]);
        }

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('status', StatusEnum::ENABLE));
        $count = $tag->getAlbums()->matching($criteria)->count();

        $criteria->orderBy(array("views" => Criteria::DESC))
            ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
            ->setMaxResults($pagingParams->getPageSize());
        $list = $tag->getAlbums()->matching($criteria)->toArray();
        $list = $this->covertHttps($list);

        return CallResultHelper::success([
            'count' => $count,
            'list' => $list
        ]);
    }

    public function query(PagingParams $pagingParams, $title = '', $cateId = 0, $enable = 1) {
        $map = [];
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title.'%'];
        }
        if (!empty($cateId)) {
            $map['cate_id'] = $cateId;
            $category = $this->albumCategoryService->info(['id' => $cateId]);
            if (!($category instanceof AlbumCategory)) {
                return CallResultHelper::success(['count' => 0, 'list' => 0]);
            }

            $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->eq('status', $enable))
                ->orderBy(array("updateTime" => Criteria::DESC))
                ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
                ->setMaxResults($pagingParams->getPageSize());
            if (!empty($title)) {
                $criteria->andWhere(Criteria::expr()->contains('title', $title));
            }

            $count = $category->getAlbums()->matching(Criteria::create()->where(Criteria::expr()->contains('title', $title)))->count();
            $list = $category->getAlbums()->matching($criteria);
            $list = $this->covertHttps($list);

            return CallResultHelper::success(['count' => $count, 'list' => $list]);
        } else {
            $ret = $this->albumService->queryByTitle($title, $pagingParams);
            $data = $ret->getData();
            $data['list'] = $this->covertHttps($data['list']);
            return CallResultHelper::success($data);
        }
    }

    /**
     * @param $title
     * @param $cateId
     * @param string $source
     * @param string $desc
     * @param string $tags
     * @return mixed
     */
    public function create($title, $cateId, $source = 'Internet', $desc = '', $tags = '') {
        $category = $this->albumCategoryService->info(['id' => $cateId]);
        if (!($category instanceof AlbumCategory)) return 'record not exists';

        $entity = new Album();
        $entity->setTitle($title);
        $entity->setDescription($desc);
        $entity->setSource($source);
        $entity->setCate($category);
        if (!empty($tags)) {
            $tagArr = explode(",", $tags);
            foreach ($tagArr as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagEntity = $this->tagsService->info(['title' => trim($tag)]);
                    if (!($tagEntity instanceof Tags)) {
                        $tagEntity = new Tags();
                        $pinyin = ByPinyin::getPinyin($tag);
                        $tagEntity->setPinyin($pinyin);
                        $tagEntity->setTitle($tag);
                        $this->tagsService->add($tagEntity);
                    }
                    $entity->addTags($tagEntity);
                }
            }
        }
        $entity->setCover(0);
        $entity->setViews(0);
        $entity->setTotal(0);
        return $this->albumService->add($entity);
    }

    /**
     * @param $id
     * @param $title
     * @param int $cateId
     * @param string $source
     * @param string $desc
     * @param string $tags
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $cateId = 0, $source = 'Internet', $desc = '', $tags = '') {

        $entity = $this->albumService->info(['id' => $id]);
        if (!($entity instanceof Album)) {
            return 'record not exists';
        }
        $entity->setTitle($title);
        $entity->setDescription($desc);
        $entity->setSource($source);
        if (!empty($cateId)) {
            $category = $this->albumCategoryService->info(['id' => $cateId]);
            if (!($category instanceof AlbumCategory)) return 'record not exists';
            $entity->setCate($category);
        }
        if (!empty($tags)) {
            $tagArr = explode(",", $tags);
            $entity->removeAllTags();
            foreach ($tagArr as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagEntity = $this->tagsService->info(['title' => trim($tag)]);
                    if (!($tagEntity instanceof Tags)) {
                        $tagEntity = new Tags();
                        $pinyin = ByPinyin::getPinyin($tag);
                        $tagEntity->setPinyin($pinyin);
                        $tagEntity->setTitle($tag);
                        $this->tagsService->add($tagEntity);
                    }
                    $entity->addTags($tagEntity);
                }
            }
        }
        return $this->albumService->flush($entity);
    }

    /**
     * @param $albumId
     * @param PagingParams $pagingParams
     * @return \by\infrastructure\base\CallResult|string
     */
    public function listPhoto($albumId, PagingParams $pagingParams) {
        $album = $this->albumService->info(['id' => $albumId]);
        if (!($album instanceof Album)) {
            return 'record not exists';
        }

        $criteria = Criteria::create()
            ->orderBy(array("albumIndex" => Criteria::DESC))
            ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
            ->setMaxResults($pagingParams->getPageSize());
        $list = $album->photos->matching($criteria)->toArray();
        $count = $album->photos->count();

        return CallResultHelper::success(['list' => $list, 'count' => $count]);
    }

    /**
     * 向相册添加照片
     * @param $albumId
     * @param $uri
     * @param int $albumIndex
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addPhoto($albumId, $uri, $albumIndex = 0) {
        $album = $this->albumService->info(['id' => $albumId]);
        if (!($album instanceof Album)) {
            return 'record not exists';
        }
        $albumPhoto = new AlbumPhoto();
        $albumPhoto->setAlbum($album);
        $albumPhoto->setAlbumIndex($albumIndex);
        $albumPhoto->setPhotoUri($uri);
        $this->albumPhotoService->add($albumPhoto);
        if ($albumPhoto->getId() > 0) {
            $album->setTotal($album->getTotal() + 1);
            $this->albumService->flush($album);
        }
        return CallResultHelper::success($albumPhoto->getId());
    }

    public function updatePhoto($photoId, $uri = false, $albumIndex = -1) {
        $photo = $this->albumPhotoService->info(['id' => $photoId]);
        if (!($photo instanceof AlbumPhoto)) {
            return 'record not exists';
        }

        if ($uri !== false && $photo->getPhotoUri() != $uri ) {
            $photo->setPhotoUri($uri);
        }
        if ($albumIndex !== -1 && $photo->getAlbumIndex() != $albumIndex) {
            $photo->setAlbumIndex($albumIndex);
        }
        $this->albumPhotoService->flush($photo);
        return CallResultHelper::success();
    }

    /**
     * 从相册中删除照片
     * @param $albumId
     * @param $photoId
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removePhoto($albumId, $photoId) {
        $album = $this->albumService->info(['id' => $albumId]);
        if (!($album instanceof Album)) {
            return 'record not exists';
        }
        $photo = $this->albumPhotoService->info(['id' => $photoId]);
        if (!($photo instanceof AlbumPhoto)) {
            return 'record not exists';
        }
        $this->albumPhotoService->delete($photo);
        if ($album->getTotal() >= 1) {
            $album->setTotal($album->getTotal() - 1);
            $this->albumService->flush($album);
        }
        return CallResultHelper::success();
    }

    /**
     * 详情
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function info($id) {
        $album = $this->albumService->info(['id' => $id]);
        if (!($album instanceof Album)) {
            return CallResultHelper::success([]);
        }
        $album->setViews($album->getViews() + 1);
        $this->albumService->flush($album);
        $photos = $this->albumPhotoService->queryAllBy(['album' => $id]);

        return CallResultHelper::success([$album, $photos]);
    }

    /**
     * 统计
     * @return \by\infrastructure\base\CallResult
     */
    public function stats() {
        $albumCount = $this->albumService->count(['status' => 1]);
        $photoCount = $this->albumPhotoService->count([]);
        return CallResultHelper::success([
            'album_count' => $albumCount,
            'photo_count' => $photoCount
        ]);
    }

}
