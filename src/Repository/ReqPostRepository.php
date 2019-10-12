<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\ReqPost;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReqPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReqPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReqPost[]    findAll()
 * @method ReqPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReqPostRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReqPost::class);
    }

    // /**
    //  * @return ReqPost[] Returns an array of ReqPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReqPost
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
