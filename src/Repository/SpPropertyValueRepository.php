<?php

namespace App\Repository;

use App\Entity\SpPropertyValue;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpPropertyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpPropertyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpPropertyValue[]    findAll()
 * @method SpPropertyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpPropertyValueRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpPropertyValue::class);
    }

    // /**
    //  * @return SpPropertyValue[] Returns an array of SpPropertyValue objects
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
    public function findOneBySomeField($value): ?SpPropertyValue
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
