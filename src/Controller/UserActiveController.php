<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;

use App\Entity\UserAccount;
use App\Message\UserRegisterMsg;
use App\ServiceInterface\ReqPostServiceInterface;
use by\component\encrypt\des\Des;
use by\infrastructure\constants\StatusEnum;
use Dbh\SfCoreBundle\Common\ByEnv;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserActiveController extends AbstractController
{
    protected $logger;
    protected $reqPostService;
    protected $userAccountService;

    public function __construct(
        UserAccountServiceInterface $userAccountService,
        ReqPostServiceInterface $reqPostService, LoggerInterface $logger)
    {
        $this->userAccountService = $userAccountService;
        $this->logger = $logger;
        $this->reqPostService = $reqPostService;
    }

    /**
     * 激活用户
     * @Route("/account-active-{activeKey}", name="user_active")
     * @param $activeKey
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function active($activeKey)
    {
        $key = ByEnv::get('APP_SECRET');
        $key = substr($key, 0, 8);
        $decode = Des::decode(base64_decode($activeKey), $key);
        $decode = json_decode($decode, JSON_OBJECT_AS_ARRAY);
        if (array_key_exists('u', $decode) && array_key_exists('t', $decode)) {
            $uid = $decode['u'];
            $time = $decode['t'];
            if ($time + 3600 < BY_APP_START_TIMESTAMP) {
                return $this->render('common/active.html.twig', ['ret' => 0, 'msg' => '激活码失效,请重新登录账户获取激活邮件']);
            }
            //TODO: 更新用户状态
            $user = $this->userAccountService->info(['id' => $uid]);
            if (!$user instanceof UserAccount) {
                return $this->render('common/active.html.twig', ['ret' => 0, 'msg' => '该账户不存在']);
            }
            if (!$user->isEmailAuth()) {
                $user->setEmailAuth(true);
                $user->setStatus(StatusEnum::ENABLE);
                $this->userAccountService->flush($user);
            }
            return $this->render('common/active.html.twig', ['ret' => 1, 'uid' => $uid]);
        }
        return $this->render('common/active.html.twig', ['ret' => 0]);
    }
}
