<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\LoginSession;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LoginSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginSession[]    findAll()
 * @method LoginSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginSessionRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoginSession::class);
    }

//    /**
//     * @return LoginSession[] Returns an array of LoginSession objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LoginSession
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
