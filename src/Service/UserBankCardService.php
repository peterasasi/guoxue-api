<?php


namespace App\Service;


use App\Repository\UserBankCardRepository;
use App\ServiceInterface\UserBankCardServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class UserBankCardService extends BaseService implements UserBankCardServiceInterface
{
    public function __construct(UserBankCardRepository $repository)
    {
        $this->repo = $repository;
    }
}
