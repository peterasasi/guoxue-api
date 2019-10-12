<?php

namespace App\Repository;

use App\Entity\SpForeCate;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpForeCate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpForeCate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpForeCate[]    findAll()
 * @method SpForeCate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpForeCateRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpForeCate::class);
    }

    // /**
    //  * @return SpForeCate[] Returns an array of SpForeCate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpForeCate
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
