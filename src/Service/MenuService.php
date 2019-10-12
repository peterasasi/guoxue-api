<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\MenuRepository;
use App\ServiceInterface\MenuServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class MenuService extends BaseService implements MenuServiceInterface
{
    /**
     * @var MenuRepository
     */
    protected $repo;

    public function __construct(MenuRepository $repository)
    {
        $this->repo = $repository;
    }
}
