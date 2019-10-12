<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/8
 * Time: 17:49
 */

namespace App\Repository;

use Dbh\SfCoreBundle\Common\BaseRepository;
use App\Entity\UserAccount;
use App\Entity\UserGrade;
use by\component\string_extend\helper\StringHelper;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserGrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGrade[]    findAll()
 * @method UserGrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class UserGradeRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserGrade::class);
    }
}
