<?php

namespace App\Repository;

use App\Entity\PlatformWalletLog;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlatformWalletLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatformWalletLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatformWalletLog[]    findAll()
 * @method PlatformWalletLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatformWalletLogRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlatformWalletLog::class);
    }

    // /**
    //  * @return PlatformWalletLog[] Returns an array of PlatformWalletLog objects
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
    public function findOneBySomeField($value): ?PlatformWalletLog
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
