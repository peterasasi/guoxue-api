<?php

namespace App\Repository;

use App\Entity\PayOrderNotifyLog;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayOrderNotifyLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayOrderNotifyLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayOrderNotifyLog[]    findAll()
 * @method PayOrderNotifyLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayOrderNotifyLogRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayOrderNotifyLog::class);
    }

    // /**
    //  * @return PayOrderNotifyLog[] Returns an array of PayOrderNotifyLog objects
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
    public function findOneBySomeField($value): ?PayOrderNotifyLog
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
