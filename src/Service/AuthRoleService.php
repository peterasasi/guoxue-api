<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 16:55
 */

namespace App\Service;


use App\Entity\AuthPolicy;
use App\Entity\AuthRole;
use App\Repository\AuthRoleRepository;
use App\ServiceInterface\AuthRoleServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class AuthRoleService extends BaseService implements AuthRoleServiceInterface
{
    /**
     * @var AuthRoleRepository
     */
    protected $repo;

    public function __construct(AuthRoleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param AuthRole $role
     * @param AuthPolicy $policy
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addPolicy(AuthRole $role, AuthPolicy $policy)
    {
        $role->addPolicy($policy);
        return $this->repo->add($role);
    }

    public function removePolicy(AuthRole $role, AuthPolicy $policy)
    {
        $role->removePolicy($policy);
        return$this->repo->flush($role);
    }


}
