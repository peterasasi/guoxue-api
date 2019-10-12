<?php

namespace App\Repository;

use App\Entity\PlatformWallet;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlatformWallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatformWallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatformWallet[]    findAll()
 * @method PlatformWallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatformWalletRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlatformWallet::class);
    }

    // /**
    //  * @return PlatformWallet[] Returns an array of PlatformWallet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlatformWallet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
