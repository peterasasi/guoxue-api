<?php

namespace App\Repository;

use App\Entity\ProfitGraph;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProfitGraph|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfitGraph|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfitGraph[]    findAll()
 * @method ProfitGraph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfitGraphRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfitGraph::class);
    }

    // /**
    //  * @return ProfitGraph[] Returns an array of ProfitGraph objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfitGraph
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
