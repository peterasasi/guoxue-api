<?php

namespace App\Repository;

use App\Entity\GxOrder;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GxOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method GxOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method GxOrder[]    findAll()
 * @method GxOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GxOrderRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GxOrder::class);
    }

    // /**
    //  * @return GxOrder[] Returns an array of GxOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GxOrder
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
