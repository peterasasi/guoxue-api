<?php

namespace App\Repository;

use App\Entity\UserWalletLog;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserWalletLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWalletLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWalletLog[]    findAll()
 * @method UserWalletLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWalletLogRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserWalletLog::class);
    }
}
