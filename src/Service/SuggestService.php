<?php


namespace App\Service;


use App\Repository\SuggestRepository;
use App\ServiceInterface\SuggestServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class SuggestService extends BaseService implements SuggestServiceInterface
{
    public function __construct(SuggestRepository $repository)
    {
        $this->repo = $repository;
    }
}
