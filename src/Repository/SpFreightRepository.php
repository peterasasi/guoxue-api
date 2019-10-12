<?php

namespace App\Repository;

use App\Entity\SpFreight;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpFreight|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpFreight|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpFreight[]    findAll()
 * @method SpFreight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpFreightRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpFreight::class);
    }

    // /**
    //  * @return SpFreight[] Returns an array of SpFreight objects
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
    public function findOneBySomeField($value): ?SpFreight
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
