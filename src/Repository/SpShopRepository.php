<?php

namespace App\Repository;

use App\Entity\SpShop;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpShop[]    findAll()
 * @method SpShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpShopRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpShop::class);
    }

    // /**
    //  * @return SpShop[] Returns an array of SpShop objects
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
    public function findOneBySomeField($value): ?SpShop
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
