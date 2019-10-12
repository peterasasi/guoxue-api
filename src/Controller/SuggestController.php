<?php


namespace App\Controller;


use App\Entity\Suggest;
use App\Entity\UserAccount;
use App\ServiceInterface\SuggestServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use by\component\paging\vo\PagingParams;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class SuggestController extends BaseSymfonyApiController
{
    protected $suggestService;
    protected $userAccountService;

    public function __construct(UserAccountServiceInterface $userAccountService, SuggestServiceInterface $suggestService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->suggestService = $suggestService;
        $this->userAccountService = $userAccountService;
    }

    public function create($content, $mobile = '', $email = '', $qq = '', $userId = 0) {
        $userInfo = [];
        $suggest = new Suggest();
        $suggest->setContent(mb_substr($content, 0, 512));
        $suggest->setUid($userId);
        $suggest->setMobile($mobile);
        $suggest->setEmail($email);
        $suggest->setQq($qq);
        $suggest->setProcStatus(0);
        if (!empty($userId)) {
            $user = $this->userAccountService->info(['id' => $userId]);
            if ($user instanceof UserAccount) {
                $userInfo['_u_mobile'] = $user->getMobile();
                $userInfo['_u_email'] = $user->getEmail();
            }
        }

        $suggest->setUserInfo(json_encode($userInfo));
        $ret = $this->suggestService->add($suggest);
        return 'success';
    }

    public function query(PagingParams $pagingParams, $procStatus = '', $mobile = '', $email = '', $qq = '') {
        $map = [];
        if (!empty($mobile)) $map['mobile'] = ['like', '%'.$mobile.'%'];
        if (!empty($email)) $map['email'] = ['like', '%'.$email.'%'];
        if (!empty($qq)) $map['qq'] = ['like', '%'.$qq.'%'];
        if ($procStatus == 0 || $procStatus == 1) $map['proc_status'] = $procStatus;

        return $this->suggestService->queryAndCount($map, $pagingParams, ['createTime' => 'desc']);
    }

    public function markProcessed($id) {
        $suggest = $this->suggestService->info(['id' => $id]);
        if (!$suggest instanceof Suggest) {
            return CallResultHelper::fail('id invalid');
        }
        $suggest->setProcStatus(1);
        $this->suggestService->flush($suggest);
        return CallResultHelper::success();
    }
}
