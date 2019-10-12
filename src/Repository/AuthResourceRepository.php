<?php

namespace App\Repository;

use App\Entity\AuthResource;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Dbh\SfCoreBundle\Common\BaseRepository;

/**
 * @method AuthResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthResource[]    findAll()
 * @method AuthResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthResourceRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthResource::class);
    }

    // /**
    //  * @return AuthResource[] Returns an array of AuthResource objects
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
    public function findOneBySomeField($value): ?AuthResource
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
