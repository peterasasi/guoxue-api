<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 11:31
 */

namespace App\AdminController;


use App\Entity\UserAccount;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserProfileServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class UserInviteController extends BaseSymfonyApiController
{

    protected $userProfile;
    protected $userAccountService;

    public function __construct(
        UserAccountServiceInterface $userAccountService,
        UserProfileServiceInterface $userProfile, KernelInterface $kernel)
    {
        $this->userProfile = $userProfile;
        $this->userAccountService = $userAccountService;
        parent::__construct($kernel);
    }

    public function query(PagingParams $pagingParams, $mobile = '')
    {
        $map = ['status' => StatusEnum::ENABLE];
        if (!empty($mobile)) {
            $ua = $this->userAccountService->info(['mobile' => $mobile]);
            if ($ua instanceof UserAccount) {
                $map['invite_uid'] = $ua->getId();
            }
        }
        $list = $this->userProfile->queryBy($map, $pagingParams);
        $count = $this->userProfile->count($map, "nickname");
        return CallResultHelper::success(['list' => $list, 'count' => $count]);
    }
}
