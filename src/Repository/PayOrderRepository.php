<?php

namespace App\Repository;

use App\Entity\PayOrder;
use Dbh\SfCoreBundle\Common\BaseRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayOrder[]    findAll()
 * @method PayOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayOrderRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayOrder::class);
    }
}
