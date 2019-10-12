<?php

namespace App\Repository;

use App\Entity\Video;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function random($pageSize = 5)
    {
        $subQ = $this->createQueryBuilder('v1')
            ->select('FLOOR( MAX(v1.id) * RAND())');

        $arr = $this->createQueryBuilder('v')
            ->where('v.id > (' . $subQ->getDQL() . ')')
            ->andWhere('v.showStatus = 1')
            ->orderBy('v.id', 'asc')
            ->setMaxResults($pageSize)->getQuery()->getArrayResult();

        return $this->formatArrayResult($arr);
    }

    // /**
    //  * @return Video[] Returns an array of Video objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
