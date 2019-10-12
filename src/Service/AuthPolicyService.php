<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 15:38
 */

namespace App\Service;


use App\Repository\AuthPolicyRepository;
use App\ServiceInterface\AuthPolicyServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class AuthPolicyService extends BaseService implements AuthPolicyServiceInterface
{
    /**
     * @var AuthPolicyRepository
     */
    protected $repo;

    public function __construct(AuthPolicyRepository $repo)
    {
        $this->repo = $repo;
    }

}
