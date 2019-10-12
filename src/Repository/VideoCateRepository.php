<?php

namespace App\Repository;

use App\Entity\VideoCate;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VideoCate|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoCate|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoCate[]    findAll()
 * @method VideoCate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoCateRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VideoCate::class);
    }

    // /**
    //  * @return VideoCate[] Returns an array of VideoCate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoCate
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
