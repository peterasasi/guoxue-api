<?php

namespace App\Repository;

use App\Entity\SpCate;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpCate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpCate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpCate[]    findAll()
 * @method SpCate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpCateRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpCate::class);
    }

    // /**
    //  * @return ShopCate[] Returns an array of ShopCate objects
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
    public function findOneBySomeField($value): ?ShopCate
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
