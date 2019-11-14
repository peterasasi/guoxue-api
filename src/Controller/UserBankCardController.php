<?php


namespace App\Controller;


use App\Common\ByCrypt;
use App\Entity\UserBankCard;
use App\Entity\UserIdCard;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserBankCardServiceInterface;
use App\ServiceInterface\UserIdCardServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class UserBankCardController extends BaseNeedLoginController
{
    protected $userBankCardService;
    protected $auditLogService;
    protected $userIdCardService;
    protected $logger;

    public function __construct(LoggerInterface $logger,
                                UserIdCardServiceInterface $userIdCardService, LoginSessionInterface $loginSession,
                                UserAccountServiceInterface $userAccountService,
                                UserBankCardServiceInterface $userBankCardService, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->userBankCardService = $userBankCardService;
        $this->userAccountService = $userAccountService;
        $this->userIdCardService = $userIdCardService;
        $this->logger = $logger;
    }


    /**
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query()
    {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid(),
            'status' => StatusEnum::ENABLE
        ];
        $field = ["id","uid","name", "createTime", "cardNo", "opening_bank", "branch_bank"];
        $list = $this->userBankCardService->queryAllBy($map, ['createTime' => 'desc'], $field);
        foreach ($list as &$vo) {
            $vo['card_no'] = ByCrypt::desDecode($vo['card_no']);
//            $vo['card_no'] = ByCrypt::hideSensitive($vo['card_no'], 4, 2, 8);
//            $vo['id_no'] = ByCrypt::desDecode($vo['id_no']);
//            $vo['id_no'] = ByCrypt::hideSensitive($vo['id_no'], 4, 3);
//            $vo['mobile'] = ByCrypt::hideSensitive($vo['mobile'], 3, 4);
//            $vo['branch_no'] = ByCrypt::hideSensitive($vo['branch_no'], 3, 3);
        }
        return $list;
    }


    /**
     * @param $id
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function unbind($id)
    {
        $this->checkLogin();
        $userBankCard = $this->userBankCardService->info(['id' => $id]);
        if (!($userBankCard instanceof UserBankCard)) return 'invalid id';
        $userBankCard->setMaster(0);
        $userBankCard->setStatus(StatusEnum::SOFT_DELETE);
        $this->userBankCardService->flush($userBankCard);
        return 'success';
    }


    /**
     * @param $cardNo
     * @param $bankName
     * @param $branchName
     * @param $name
     * @return mixed|string
     * @throws \by\component\exception\NotLoginException
     */
    public function bind($cardNo, $bankName, $branchName, $name)
    {
        $this->checkLogin();
//        $idCard = $this->userIdCardService->info(['uid' => $this->getUid()]);
//        if (!$idCard instanceof UserIdCard || $idCard->getVerify() !== 1) {
//            return '请先进行实名认证';
//        }
//
//        if ($name !==  $idCard->getRealName()) {
//            return '绑定卡的姓名必须和实名认证一致';
//        }

        $bankCard = $this->userBankCardService->info(['card_no' => $cardNo, 'uid' => $this->getUid(), 'status' => StatusEnum::ENABLE]);
        if ($bankCard instanceof UserBankCard) {
            return '卡已绑定';
        }


        $bankCard = new UserBankCard();
        $bankCard->setCvn2('');
        $bankCard->setOpeningBank($bankName);
        $bankCard->setBranchBank($branchName);
        $bankCard->setExpireDate('');
        $bankCard->setBillDate(intval(0));
        $bankCard->setRepaymentDate(intval(0));
        $bankCard->setUid($this->getUid());
        $bankCard->setName($name);
        $bankCard->setIdNo('');
        $bankCard->setFrontImgId('');
        $bankCard->setFrontImg('');
        $bankCard->setMaster(0);
        $bankCard->setMobile('');
        $bankCard->setCardUsage(UserBankCard::UsageBalanceCard);
        $bankCard->setCardType(UserBankCard::TypeDebit);
        $bankCard->setCardNo($cardNo);
        $bankCard->setStatus(StatusEnum::ENABLE);
        $bankCard->setCardCode('');
        $bankCard->setVerify(1);

        $this->userBankCardService->add($bankCard);
        return CallResultHelper::success($bankCard->getId());
    }
}
