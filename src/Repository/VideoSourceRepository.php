<?php

namespace App\Repository;

use App\Entity\VideoSource;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VideoSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoSource[]    findAll()
 * @method VideoSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoSourceRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VideoSource::class);
    }

    // /**
    //  * @return VideoSource[] Returns an array of VideoSource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoSource
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
