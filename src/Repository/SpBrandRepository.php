<?php

namespace App\Repository;

use App\Entity\SpBrand;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpBrand[]    findAll()
 * @method SpBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpBrandRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpBrand::class);
    }

    // /**
    //  * @return SpBrand[] Returns an array of SpBrand objects
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
    public function findOneBySomeField($value): ?SpBrand
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
