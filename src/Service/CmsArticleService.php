<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Entity\Datatree;
use App\Repository\CmsArticleRepository;
use App\ServiceInterface\CmsArticleServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Doctrine\ORM\Query\Expr\Join;

class CmsArticleService extends BaseService implements CmsArticleServiceInterface
{
    protected $repo;

    public function __construct(CmsArticleRepository $repository)
    {
        $this->repo = $repository;
    }


    public function queryAdmin($title, $categoryId, PagingParams $pagingParams, $containDetail = 0, $status = '')
    {
        $qb = $this->repo->createQueryBuilder('cms');
        $qb->leftJoin(Datatree::class, "dt", Join::WITH, "dt.code = cms.categoryId")
            ->leftJoin("cms.tags", "t")
            ->select("cms.contentImgList,cms.comeFrom,GROUP_CONCAT(DISTINCT t.title ORDER BY t.id DESC SEPARATOR ',') as tags , dt.name as cate_name,cms.id, cms.views,cms.likes,cms.publishStatus, cms.categoryId,cms.createTime,cms.updateTime,cms.cover,cms.authorId,cms.authorNick, cms.title, cms.summary")
            ->groupBy("cms.id");

        $map = [
            'status' => StatusEnum::ENABLE
        ];
        $qb->andWhere('cms.status = 1');
        if ($containDetail == 1) {
            $qb->addSelect("cms.content");
        }

        if ($categoryId > 0) {
            $map['category_id'] = $categoryId;
            $qb->andWhere('cms.categoryId = :cateId')
                ->setParameter('cateId', $categoryId);
        }
        if (!empty($status)) {
            $map['publish_status'] = $status;
            $qb->andWhere('cms.publishStatus = :publishStatus')
                ->setParameter('publishStatus', $status);
        }
        if (!empty($title)) {
            $map['title'] = ['like', $title.'%'];
            $qb->andWhere('cms.title = :title')
                ->setParameter('title', $title);
        }
        $qb->orderBy("cms.updateTime", "DESC");
        $first = $pagingParams->getPageIndex() * $pagingParams->getPageSize();

        $list = $qb->setFirstResult($first)->setMaxResults($pagingParams->getPageSize())
            ->getQuery()->getArrayResult();

        $list = $this->repo->formatArrayResult($list);
        $count = $this->count($map);

        return CallResultHelper::success([
            'count' => $count,
            'list' => $list
        ]);
    }


}
