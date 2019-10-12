<?php

namespace App\Repository;

use App\Entity\SpGoods;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpGoods|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpGoods|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpGoods[]    findAll()
 * @method SpGoods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpGoodsRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpGoods::class);
    }

    // /**
    //  * @return SpGoods[] Returns an array of SpGoods objects
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
    public function findOneBySomeField($value): ?SpGoods
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
