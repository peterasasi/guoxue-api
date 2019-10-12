<?php

namespace App\Repository;

use App\Entity\SpGoodsSku;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpGoodsSku|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpGoodsSku|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpGoodsSku[]    findAll()
 * @method SpGoodsSku[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpGoodsSkuRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpGoodsSku::class);
    }

    // /**
    //  * @return SpGoodsSku[] Returns an array of SpGoodsSku objects
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
    public function findOneBySomeField($value): ?SpGoodsSku
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
