<?php

namespace App\Repository;

use App\Entity\CmsArticle;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CmsArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsArticle[]    findAll()
 * @method CmsArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsArticleRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CmsArticle::class);
    }

    // /**
    //  * @return CmsArticle[] Returns an array of CmsArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsArticle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
