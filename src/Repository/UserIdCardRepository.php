<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 17:50
 */

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\UserIdCard;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserIdCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserIdCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserIdCard[]    findAll()
 * @method UserIdCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class UserIdCardRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserIdCard::class);
    }
}
