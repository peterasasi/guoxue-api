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
use App\Entity\CmsArticle;
use App\Entity\Datatree;
use App\Entity\Tags;
use App\Entity\UserProfile;
use App\Helper\MarkdownHelper;
use App\Helper\SystemDtCode;
use App\ServiceInterface\CmsArticleServiceInterface;
use App\ServiceInterface\DatatreeServiceInterface;
use App\ServiceInterface\TagsServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpKernel\KernelInterface;

class CmsArticleController extends BaseSymfonyApiController
{
    protected $cmsArticleService;
    protected $tagsService;
    protected $userProfileService;
    protected $datatreeService;

    public function __construct(DatatreeServiceInterface $datatreeService, UserProfileServiceInterface $userProfile, TagsServiceInterface $tagsService, CmsArticleServiceInterface $articleService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->datatreeService = $datatreeService;
        $this->cmsArticleService = $articleService;
        $this->tagsService = $tagsService;
        $this->userProfileService = $userProfile;
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws OptimisticLockException
     */
    public function info($id) {
        $article = $this->cmsArticleService->info(['id' => $id]);

        if (!($article instanceof CmsArticle)) {
            return 'invalid id';
        }
        $cate = $this->datatreeService->info(['code' => $article->getCategoryId()]);
        if (!($cate instanceof Datatree)) {
            return 'invalid category';
        }

        $ret = [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'author_id' => $article->getAuthorId(),
            'author_nick' => $article->getAuthorNick(),
            'cate' => $article->getCategoryId(),
            'cate_name' => $cate->getName(),
            'come_from' => $article->getComeFrom(),
            'views' => $article->getViews(),
            'summary' => $article->getSummary(),
            'create_time' => $article->getCreateTime(),
            'update_time' => $article->getUpdateTime(),
            'tags' => $article->getTags()->map(function ($tag) {
                if ($tag instanceof Tags) {
                    return [
                        'id' => $tag->getId(),
                        'title' => $tag->getTitle(),
                        'pinyin' => $tag->getPinyin()
                    ];
                }
            }),
            'cover' =>  $article->getCover(),
            'content' => $article->getContent(),
        ];
        $article->setViews($article->getViews() + 1);
        $this->cmsArticleService->flush($article);
        return CallResultHelper::success($ret);
    }

    public function queryArticleBy($title, $categoryId, PagingParams $pagingParams) {
        return $this->cmsArticleService->queryAdmin($title, $categoryId, $pagingParams, 0, CmsArticle::PublishStatusPublished);
    }

    public function cate() {
        return $this->datatreeService->queryAllBy(['code' => ['like', SystemDtCode::ArticlePosition.'___']]);
    }

    public function queryAdmin(PagingParams $pagingParams, $containDetail = 0, $title = '', $categoryId = '', $status = '') {
        return $this->cmsArticleService->queryAdmin($title, $categoryId, $pagingParams, $containDetail, $status);
    }

    public function delete($id) {
        $article = $this->cmsArticleService->info(['id' => $id]);
        if (!($article instanceof CmsArticle)) return 'invalid id';
        $article->setStatus(StatusEnum::SOFT_DELETE);
        $this->cmsArticleService->flush($article);
        return CallResultHelper::success();
    }


    /**
     * 上架
     * @param $id
     * @return string
     */
    public function publish($id) {
        return $this->updateStatus($id, CmsArticle::PublishStatusPublished);
    }

    /**
     * 重新设置为草稿
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     */
    public function draft($id) {
        return $this->updateStatus($id, CmsArticle::PublishStatusDraft);
    }

    protected function updateStatus($id, $status) {
        $article = $this->cmsArticleService->info(['id' => $id]);
        if (!($article instanceof CmsArticle)) return 'invalid id';
        $article->setPublishStatus($status);
        $this->cmsArticleService->flush($article);
        return CallResultHelper::success();
    }

    /**
     * @param $title
     * @param $categoryId
     * @param $summary
     * @param $content
     * @param $cover
     * @param $tags
     * @param string $comeFrom
     * @return \by\infrastructure\base\CallResult|string
     */
    public function create($title, $categoryId, $summary, $content, $cover, $tags, $comeFrom = '') {
        $tags = explode(",", $tags);
        $authorId = $this->getUid();
        $userProfile = $this->userProfileService->info(['user' => $authorId]);
        if (!($userProfile instanceof UserProfile)) {
            return 'invalid uid';
        }
        if (strpos($categoryId, SystemDtCode::ArticlePosition) !== 0) {
            return 'invalid category id';
        }
        $dt = $this->datatreeService->info(['code' => $categoryId]);
        if (!($dt instanceof Datatree)) {
            return 'invalid category id';
        }
        $entity = new CmsArticle();
        $entity->setComeFrom($comeFrom);
        $entity->setTitle($title);
        $entity->setSummary($summary);
        $entity->setContent($content);
        $entity->setCover($cover);
        $entity->setAuthorId($userProfile->getUid());
        $entity->setAuthorNick($userProfile->getNickname());
        $entity->setCategoryId($categoryId);
        $entity->setLikes(0);
        $entity->setViews(0);
        $entity->setCreateTime(time());
        $entity->setUpdateTime(time());
        $entity->setPublishStatus(CmsArticle::PublishStatusDraft);
        $entity->setStatus(StatusEnum::ENABLE);

        $entity->setContentImgList($this->getStrImgs($entity->getContent()));
        foreach ($tags as $t) {
            $tagEntity = new Tags();
            $tagEntity->setTitle($t);
            $tagEntity->setPinyin(ByPinyin::getPinyin($t));
            $tagEntity = $this->tagsService->addNotExists($tagEntity);
            $entity->addTag($tagEntity);
        }
        $this->cmsArticleService->add($entity);
        return CallResultHelper::success();
    }

    protected function getStrImgs($content) {
        $imgList = MarkdownHelper::getImgUrlFromMarkdown($content, 3);
        $strImgsList = '';
        foreach ($imgList as $vo) {
            if (strlen($strImgsList) > 0) $strImgsList .= ',';
            $strImgsList .= $vo['img'];
        }
        return $strImgsList;
    }

    /**
     * @param $id
     * @param $comeFrom
     * @param $title
     * @param $categoryId
     * @param $summary
     * @param $content
     * @param $cover
     * @param $tags
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws OptimisticLockException
     */
    public function update($id, $comeFrom, $title, $categoryId, $summary, $content, $cover, $tags) {
        $article = $this->cmsArticleService->info(['id' => $id]);
        if (!($article instanceof CmsArticle)) {
            return 'invalid id';
        }
        $tags = explode(",", $tags);
        $article->setComeFrom($comeFrom);
        $article->setTitle($title);
        $article->setCategoryId($categoryId);
        $article->setSummary($summary);
        $article->setContent($content);
        $article->setContentImgList($this->getStrImgs($article->getContent()));
        $article->setCover($cover);
        $removeTags = $this->getRemoveTags($article->getTags(), $tags);
        $addTagTitle = $this->getAddTags($article->getTags(), $tags);
        foreach ($removeTags as $t) {
            $article->removeTag($t);
        }
        foreach ($addTagTitle as $tagTitle) {
            $tagEntity = new Tags();
            $tagEntity->setTitle($tagTitle);
            $tagEntity->setPinyin(ByPinyin::getPinyin($tagTitle));
            $tagEntity = $this->tagsService->addNotExists($tagEntity);
            $article->addTag($tagEntity);
        }
        $this->cmsArticleService->flush($article);
        return CallResultHelper::success();
    }

    protected function getRemoveTags(Collection $existsTags, Array $tags) {
        $removeTags = [];
        foreach ($existsTags as $t) {
            $rFlag = true;
            foreach ($tags as $updateTag) {
                if ($t->getTitle() == $updateTag) {
                    $rFlag = false;
                    break;
                }
            }
            if ($rFlag) {
                array_push($removeTags, $t);
            }
        }
        return $removeTags;
    }

    protected function getAddTags(Collection $existsTags, Array $tags) {
        $addTags = [];
        foreach ($tags as $addTag) {
            $rFlag = true;
            foreach ($existsTags as $t) {
                if ($t->getTitle() == $addTag) {
                    $rFlag = false;
                    break;
                }
            }

            if ($rFlag) {
                array_push($addTags, $addTag);
            }
        }
        return $addTags;
    }
}
