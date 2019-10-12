<?php

namespace App\Repository;

use App\Entity\AlbumModel;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AlbumModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumModel[]    findAll()
 * @method AlbumModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumModelRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlbumModel::class);
    }

    // /**
    //  * @return AlbumModel[] Returns an array of AlbumModel objects
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
    public function findOneBySomeField($value): ?AlbumModel
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
