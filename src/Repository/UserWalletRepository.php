<?php

namespace App\Repository;

use App\Entity\UserWallet;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserWallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWallet[]    findAll()
 * @method UserWallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWalletRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserWallet::class);
    }
}
