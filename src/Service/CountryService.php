<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\CountryRepository;
use App\ServiceInterface\CountryServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class CountryService extends BaseService implements CountryServiceInterface
{
    /**
     * @var CountryRepository
     */
    protected $repo;

    public function __construct(CountryRepository $repository)
    {
        $this->repo = $repository;
    }
}
