<?php


namespace App\Service;


use App\Entity\PlatformWallet;
use App\Entity\PlatformWalletLog;
use App\Repository\PlatformWalletLogRepository;
use App\Repository\PlatformWalletRepository;
use App\ServiceInterface\PlatformWalletServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\BaseService;
use Doctrine\DBAL\LockMode;

class PlatformWalletService extends BaseService implements PlatformWalletServiceInterface
{
    protected $pfwLogRepo;

    public function __construct(PlatformWalletLogRepository $pfwLogRepo, PlatformWalletRepository $repository)
    {
        $this->repo = $repository;
        $this->pfwLogRepo = $pfwLogRepo;
    }


    /**
     * @param $wid
     * @param float $money
     * @param string $note
     * @return \by\infrastructure\base\CallResult|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addMoneyTo($wid, $money, $note = '增加金额')
    {
        $wallet = $this->findById($wid, LockMode::PESSIMISTIC_WRITE);
        if ($wallet instanceof PlatformWallet) {
            $wallet->setBalance($wallet->getBalance() + $money);
            $log = new PlatformWalletLog();
            $log->setRemark($note);
            $log->setIncome($money);
            $log->setWalletId($wid);
            $this->pfwLogRepo->add($log);
            $this->flush($wallet);
            return CallResultHelper::success();
        }
        return CallResultHelper::fail();
    }


}
