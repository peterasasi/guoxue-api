<?php


namespace App\Service;


use App\Repository\UserGradeRepository;
use App\ServiceInterface\UserGradeServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserGradeService extends BaseService implements UserGradeServiceInterface
{
    public function __construct(UserGradeRepository $repository)
    {
        $this->repo = $repository;
    }
}
