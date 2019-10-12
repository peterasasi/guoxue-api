<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 14:51
 */

namespace App\Service;


use Dbh\SfCoreBundle\Common\BaseService;
use App\Repository\AuthResourceRepository;
use App\ServiceInterface\AuthResourceServiceInterface;

class AuthResourceService extends BaseService implements AuthResourceServiceInterface
{
    /**
     * @var AuthResourceRepository
     */
    protected $repo;

    public function __construct(AuthResourceRepository $repo)
    {
        $this->repo = $repo;
    }
}
