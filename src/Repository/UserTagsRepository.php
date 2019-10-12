<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\UserTags;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTags[]    findAll()
 * @method UserTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTagsRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserTags::class);
    }

//    /**
//     * @return Clients[] Returns an array of Clients objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clients
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
