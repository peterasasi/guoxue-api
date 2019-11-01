<?php

namespace App\Repository;

use App\Entity\XftMerchant;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method XftMerchant|null find($id, $lockMode = null, $lockVersion = null)
 * @method XftMerchant|null findOneBy(array $criteria, array $orderBy = null)
 * @method XftMerchant[]    findAll()
 * @method XftMerchant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XftMerchantRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, XftMerchant::class);
    }

    // /**
    //  * @return XftMerchant[] Returns an array of XftMerchant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('x.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?XftMerchant
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
