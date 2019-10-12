<?php

namespace App\Repository;

use App\Entity\Album;
use by\component\paging\vo\PagingParams;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function findByTitle($title, PagingParams $pagingParams) {
        $query = $this->createQueryBuilder("a")
            ->leftJoin('a.cate', 'c')
            ->addSelect('a');
        $map = [
            'status' => 1
        ];
        if (!empty($title)) {
            $query->andWhere('a.title like  :title')
            ->setParameter('title', '%'.$title.'%');
            $map['title'] = ['like', '%'.$title.'%'];
        }
        $list = $query->andWhere('a.status = 1')->orderBy('a.updateTime', 'DESC')
        ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
        ->setMaxResults($pagingParams->getPageSize())
        ->getQuery()
        ->execute();
        $count = $this->enhanceCount($map);
        return CallResultHelper::success([
            'count' => $count,
            'list' => $list
        ]);
    }

    // /**
    //  * @return Album[] Returns an array of Album objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Album
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
