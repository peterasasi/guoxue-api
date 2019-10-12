<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\AuthRole;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuthRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthRole[]    findAll()
 * @method AuthRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthRoleRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthRole::class);
    }

    // /**
    //  * @return AuthRole[] Returns an array of AuthRole objects
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
    public function findOneBySomeField($value): ?AuthRole
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
