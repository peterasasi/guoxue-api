<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 17:50
 */

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\UserProfile;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class UserProfileRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }
}
