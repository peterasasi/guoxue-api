<?php

namespace App\Repository;

use App\Entity\SpGoodsPlace;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpGoodsPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpGoodsPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpGoodsPlace[]    findAll()
 * @method SpGoodsPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpGoodsPlaceRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpGoodsPlace::class);
    }

    // /**
    //  * @return SpGoodsPlace[] Returns an array of SpGoodsPlace objects
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
    public function findOneBySomeField($value): ?SpGoodsPlace
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
