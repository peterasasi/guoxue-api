<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\CityArea;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CityArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityArea[]    findAll()
 * @method CityArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityAreaRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CityArea::class);
    }

    // /**
    //  * @return CityArea[] Returns an array of CityArea objects
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
    public function findOneBySomeField($value): ?CityArea
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
