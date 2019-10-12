<?php

namespace App\Repository;

use App\Entity\ApiReqHis;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApiReqHis|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiReqHis|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiReqHis[]    findAll()
 * @method ApiReqHis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiReqHisRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApiReqHis::class);
    }

    // /**
    //  * @return ApiReqHis[] Returns an array of ApiReqHis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiReqHis
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
