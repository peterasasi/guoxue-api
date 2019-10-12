<?php


namespace App\Service;


use App\Repository\VideoSourceRepository;
use App\ServiceInterface\VideoSourceServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;

class VideoSourceService extends BaseService implements VideoSourceServiceInterface
{
    public function __construct(VideoSourceRepository $repository)
    {
        $this->repo = $repository;
    }
}
