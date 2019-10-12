<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/22
 * Time: 16:30
 */

namespace App\Service;


use App\Repository\DatatreeRepository;
use App\ServiceInterface\DatatreeServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class DatatreeService extends BaseService implements DatatreeServiceInterface
{
    /**
     * @var DatatreeRepository
     */
    protected $repo;

    public function __construct(DatatreeRepository $repo)
    {
        $this->repo = $repo;
    }
}
