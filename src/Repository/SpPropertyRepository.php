<?php

namespace App\Repository;

use App\Entity\SpProperty;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpProperty[]    findAll()
 * @method SpProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpPropertyRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpProperty::class);
    }

    // /**
    //  * @return SpProperty[] Returns an array of SpProperty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpProperty
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
