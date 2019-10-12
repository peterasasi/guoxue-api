<?php


namespace App\Service;


use App\Repository\VideoCateRepository;
use App\ServiceInterface\VideoCateServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class VideoCateService extends BaseService implements VideoCateServiceInterface
{
    public function __construct(VideoCateRepository $repository)
    {
        $this->repo = $repository;
    }
}
