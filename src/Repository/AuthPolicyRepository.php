<?php

namespace App\Repository;

use App\Entity\AuthPolicy;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuthPolicy|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthPolicy|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthPolicy[]    findAll()
 * @method AuthPolicy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthPolicyRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthPolicy::class);
    }

    // /**
    //  * @return AuthPolicy[] Returns an array of AuthPolicy objects
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
    public function findOneBySomeField($value): ?AuthPolicy
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
