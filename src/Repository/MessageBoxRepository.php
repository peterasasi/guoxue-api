<?php

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\MessageBox;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MessageBox|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageBox|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageBox[]    findAll()
 * @method MessageBox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageBoxRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MessageBox::class);
    }

    // /**
    //  * @return MessageBox[] Returns an array of MessageBox objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageBox
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
