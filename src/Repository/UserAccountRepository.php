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
use by\component\string_extend\helper\StringHelper;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccount[]    findAll()
 * @method UserAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class UserAccountRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserAccount::class);
    }

    public function queryByRoleName($roleName, $order) {
        $builder = $this->getEntityManager()->createQueryBuilder();
        // alias 是随便取一个，不影响
        $alias = "s.";
        if (empty($fields)) {
            $fields = $this->getEntityManager()->getClassMetadata($this->getEntityName())
                ->getColumnNames();
        }

        // 补充字段
        for ($i = 0; $i < count($fields); $i++) {
            $fields[$i] = $alias . StringHelper::toCamelCase($fields[$i]);
        }
        $builder->select($fields)
//            ->leftJoin()
            ->from($this->getEntityName(), rtrim($alias, "."));
        // 解析排序
        foreach ($order as $orderKey => $orderDir) {
            $builder->addOrderBy($alias . $orderKey, $orderDir);
        }
        $map = ['role_name' => $roleName];
        $query = $this->parseMap($map, $builder, $alias)->getQuery();

        return $this->formatArrayResult($query->getArrayResult());
    }
}
