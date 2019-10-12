<?php

namespace App\Repository;

use App\Entity\AlbumPhoto;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AlbumPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumPhoto[]    findAll()
 * @method AlbumPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumPhotoRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlbumPhoto::class);
    }

    // /**
    //  * @return AlbumPhoto[] Returns an array of AlbumPhoto objects
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
    public function findOneBySomeField($value): ?AlbumPhoto
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
