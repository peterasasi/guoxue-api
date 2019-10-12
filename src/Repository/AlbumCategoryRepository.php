<?php

namespace App\Repository;

use App\Entity\AlbumCategory;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AlbumCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumCategory[]    findAll()
 * @method AlbumCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumCategoryRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlbumCategory::class);
    }

    // /**
    //  * @return AlbumCategory[] Returns an array of AlbumCategory objects
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
    public function findOneBySomeField($value): ?AlbumCategory
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
