<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Repository\MessageRepository;
use App\ServiceInterface\MessageServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class MessageService extends BaseService implements MessageServiceInterface
{
    /**
     * @var MessageRepository
     */
    protected $repo;

    public function __construct(MessageRepository $repository)
    {
        $this->repo = $repository;
    }
}
