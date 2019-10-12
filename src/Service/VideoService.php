<?php


namespace App\Service;

use App\Entity\Datatree;
use App\Repository\VideoRepository;
use App\ServiceInterface\VideoServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Doctrine\ORM\Query\Expr\Join;

class VideoService extends BaseService implements VideoServiceInterface
{
    public function __construct(VideoRepository $repository)
    {
        $this->repo = $repository;
    }

    public function random($pageSize = 10)
    {
        return $this->repo->random($pageSize);
    }


    public function queryAdminWithTags($title, $categoryId, PagingParams $pagingParams, $status = StatusEnum::ENABLE)
    {
        $qb = $this->repo->createQueryBuilder('vid');
        $qb->leftJoin(Datatree::class, "dt", Join::WITH, "dt.code = vid.cateId")
            ->leftJoin("vid.tags", "t")
            ->select("vid.language,vid.area,vid.end,vid.directors,vid.actors,vid.year,vid.recommend,GROUP_CONCAT(DISTINCT t.title ORDER BY t.id DESC SEPARATOR ',') as tags , dt.name as cate_name,vid.id, vid.views,vid.showStatus, vid.cateId,vid.createTime,vid.updateTime,vid.cover,vid.uploaderId,vid.uploadNick, vid.title, vid.description")
            ->groupBy("vid.id");

        $map = [
        ];

        if ($categoryId > 0) {
            $map['cate_id'] = $categoryId;
            $qb->andWhere('vid.cateId = :cateId')
                ->setParameter('cateId', $categoryId);
        }

        if (intval($status) == StatusEnum::ENABLE || intval($status) == StatusEnum::DISABLED) {
            $map['show_status'] = $status;
            $qb->andWhere('vid.showStatus = :showStatus')
                ->setParameter('showStatus', $status);
        }
        if (!empty($title)) {
            $map['title'] = ['like', '%'.$title . '%'];
            $qb->andWhere('vid.title like :title')
                ->setParameter('title', '%'.$title.'%');
        }
        $qb->orderBy("vid.updateTime", "DESC");
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
