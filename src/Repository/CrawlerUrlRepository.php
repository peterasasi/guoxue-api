<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\CrawlerUrl;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrawlerUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrawlerUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrawlerUrl[]    findAll()
 * @method CrawlerUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrawlerUrlRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrawlerUrl::class);
    }

    // /**
    //  * @return CrawlerUrl[] Returns an array of CrawlerUrl objects
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
    public function findOneBySomeField($value): ?CrawlerUrl
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
