<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\Datatree;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Datatree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Datatree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Datatree[]    findAll()
 * @method Datatree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatatreeRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Datatree::class);
    }

    // /**
    //  * @return Datatree[] Returns an array of Datatree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Datatree
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
