<?php


namespace App\AdminController;


use App\Entity\Suggest;
use App\Entity\UserAccount;
use App\ServiceInterface\SuggestServiceInterface;
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

    public function create($content, $userId = 0)
    {
        $suggest = new Suggest();
        $suggest->setContent(mb_substr($content, 0, 512));
        $suggest->setUserInfo('');
        $suggest->setUid($userId);

        if (!empty($userId)) {
            $user = $this->userAccountService->info(['id' => $userId]);
            if ($user instanceof UserAccount) {
                $userInfo = [
                    'mobile' => $user->getMobile(),
                ];
                $suggest->setUserInfo(json_encode($userInfo));
            }
        }

        $ret = $this->suggestService->add($suggest);
        return 'success';
    }

    public function query(PagingParams $pagingParams)
    {
        $map = [];
        //return $this->suggestService->queryAndCount($map, $pagingParams, ['createTime' => 'desc']);
        $res = $this->suggestService->queryBy($map, $pagingParams, ['createTime' => 'desc']);
        if (count($res) > 0) {
            foreach ($res as &$val) { // 获取用户昵称和邮箱
                $val['username'] = '';
                $val['Email'] = '';
                $val['mobile'] = '';
                if ($val['uid'] > 0) {
                    $userInfo = $this->userAccountService->info(['id' => $val['uid']]);
                    if ($userInfo instanceof UserAccount) {
                        $val['username'] = $userInfo->getUsername();
                        $val['Email'] = $userInfo->getEmail();
                        $val['mobile'] = $userInfo->getMobile();
                    }
                }
            }
        }
        return $res;


    }
}
