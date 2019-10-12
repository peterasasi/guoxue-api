<?php


namespace App\AdminController;


use App\Common\ByPinyin;
use App\Entity\Tags;
use App\Entity\Video;
use App\Entity\VideoCate;
use App\ServiceInterface\TagsServiceInterface;
use App\ServiceInterface\VideoCateServiceInterface;
use App\ServiceInterface\VideoServiceInterface;
use App\ServiceInterface\VideoSourceServiceInterface;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\KernelInterface;

class VideoController extends BaseNeedLoginController
{
    protected $videoCateService;
    protected $videoService;
    protected $videoSourceService;
    protected $tagsService;

    public function __construct(
        TagsServiceInterface $tagsService,
        VideoSourceServiceInterface $videoSourceService,
        VideoServiceInterface $videoService, VideoCateServiceInterface $videoCateService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->videoCateService = $videoCateService;
        $this->videoService = $videoService;
        $this->videoSourceService = $videoSourceService;
        $this->tagsService = $tagsService;
    }

    /**
     * 视频下架
     * @param $id
     * @return CallResult
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function offline($id) {
        $video = $this->videoService->findById($id);
        if (!$video instanceof Video) {
            return CallResultHelper::fail('id invalid');
        }
        $video->setShowStatus(StatusEnum::DISABLED);
        $this->videoService->flush($video);
        return CallResultHelper::success();
    }

    /**
     * 视频上架
     * @param $id
     * @return CallResult
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function online($id) {
        $video = $this->videoService->findById($id);
        if (!$video instanceof Video) {
            return CallResultHelper::fail('id invalid');
        }
        $video->setShowStatus(StatusEnum::ENABLE);
        $this->videoService->flush($video);
        return CallResultHelper::success();
    }

    /**
     * @param PagingParams $pagingParams
     * @param int $showStatus
     * @param int $cateId
     * @param string $title
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $showStatus = StatusEnum::ENABLE, $cateId = 0, $title = '')
    {
        return $this->videoService->queryAdminWithTags($title, $cateId, $pagingParams, intval($showStatus));
    }

    /**
     * @param $directors
     * @param $actors
     * @param $area
     * @param $isEnd
     * @param $language
     * @param $title
     * @param $cover
     * @param $description
     * @param $cateId
     * @param $year
     * @param string $tags
     * @param int $views
     * @return mixed|string
     * @throws NotLoginException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create($directors, $actors, $area, $isEnd, $language, $title, $cover, $description, $cateId, $year, $tags = '', $views = 0) {

        $this->checkLogin();

        $cate = $this->videoCateService->findById($cateId);
        if (!$cate instanceof VideoCate) return '视频分类id错误';
        if (intval($year) > 3000 && intval($year) < 1000) {
            return  'year invalid';
        }

        $cate->setVidCnt($cate->getVidCnt() + 1);

        $this->videoCateService->flush($cate);

        $entity = new Video();
        $entity->setEnd($isEnd);
        $entity->setDirectors($directors);
        $entity->setActors($actors);
        $entity->setArea($area);
        $entity->setLanguage($language);
        $entity->setRecommend(0);
        $entity->setYear(intval($year));
        $entity->setViews($views);
        $entity->setCover($cover);
        $entity->setTitle($title);
        $entity->setDescription($description);
        $entity->setCateId($cateId);
        $entity->setUploaderId($this->getUid());
        $entity->setUploadNick($this->getLoginUserNick());
        $entity->setShowStatus(StatusEnum::DISABLED);

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

        return $this->videoService->add($entity);
    }


    /**
     * @param $id
     * @param $directors
     * @param $actors
     * @param $area
     * @param $language
     * @param $isEnd
     * @param $title
     * @param $cover
     * @param $description
     * @param $year
     * @param $cateId
     * @param string $tags
     * @return CallResult|string
     * @throws NotLoginException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update($id, $directors, $actors, $area, $language, $isEnd, $title, $cover, $description, $year, $cateId, $tags = '') {
        $this->checkLogin();
        $year = intval($year);
        $entity = $this->videoService->findById($id);
        if (!$entity instanceof Video) return 'id 不存在';
        if ($year > 3000 && $year < 1000) {
            return  'year invalid';
        }
        $entity->setEnd($isEnd);
        $entity->setDirectors($directors);
        $entity->setActors($actors);
        $entity->setArea($area);
        $entity->setLanguage($language);
        $entity->setYear($year);
        $entity->setCover($cover);
        $entity->setTitle($title);
        $entity->setDescription($description);
        $entity->setCateId($cateId);
        $entity->setUploaderId($this->getUid());
        $entity->setUploadNick($this->getLoginUserNick());

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

        $this->videoService->flush($entity);
        return CallResultHelper::success();
    }

    /**
     * 推荐该视频
     * @param $id
     * @return CallResult
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NotLoginException
     */
    public function recommend($id) {
        $this->checkLogin();
        $video = $this->videoService->findById($id);
        if (!$video instanceof Video) {
            return CallResultHelper::fail('id not exist');
        }
        if ($video->getRecommend() != 1) {
            $video->setRecommend(1);
            $this->videoService->flush($video);
        }
        return CallResultHelper::success();
    }

    /**
     * 推荐下架
     * @param $id
     * @return CallResult
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NotLoginException
     */
    public function unrecommend($id) {
        $this->checkLogin();
        $video = $this->videoService->findById($id);
        if (!$video instanceof Video) {
            return CallResultHelper::fail('id not exist');
        }
        if ($video->getRecommend() != 0) {
            $video->setRecommend(0);
            $this->videoService->flush($video);
        }
        return CallResultHelper::success();
    }

    public function show($id) {
        $entity = $this->videoService->findById($id);
        if (!$entity instanceof Video) return CallResultHelper::fail('id not exist');

        if ($entity->getShowStatus() == StatusEnum::SOFT_DELETE) {
            return CallResultHelper::fail('该视频已被删除');
        }
        $entity->setShowStatus(StatusEnum::ENABLE);

        return CallResultHelper::success();
    }

    public function hide($id) {
        $entity = $this->videoService->findById($id);
        if (!$entity instanceof Video) return CallResultHelper::fail('id not exits');

        if ($entity->getShowStatus() == StatusEnum::SOFT_DELETE) {
            return CallResultHelper::fail('该视频已被删除');
        }

        $entity->setShowStatus(StatusEnum::DISABLED);

        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @return CallResult|string
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete($id) {
        $cnt = $this->videoSourceService->count(['vid' => $id]);
        if ($cnt > 0) return '不能删除有视频源的视频,请先删除该视频源';

        $entity = $this->videoService->findById($id);
        if (!$entity instanceof Video) return CallResultHelper::success();

        $entity->setShowStatus(StatusEnum::SOFT_DELETE);

        $cate = $this->videoCateService->findById($entity->getCateId());
        if ($cate instanceof VideoCate) {
            $cate->setVidCnt($cate->getVidCnt() - 1);
            $this->videoCateService->flush($cate);
        }

        $this->videoService->flush($entity);

        return CallResultHelper::success();
    }

}
