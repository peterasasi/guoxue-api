<?php


namespace App\AdminController;


use App\Common\GxGlobalConfig;
use App\Common\PayWayConst;
use App\Entity\GxOrder;
use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Entity\UserWallet;
use App\Helper\CodeGenerator;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use App\ServiceInterface\UserWalletServiceInterface;
use App\ServiceInterface\WithdrawServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\DBAL\LockMode;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;

class GxOrderController extends BaseNeedLoginController
{
    protected $gxOrderService;
    protected $gxConfig;
    protected $profitGraphService;
    protected $withdrawService;
    protected $logger;
    protected $userWalletService;

    public function __construct(
        LoggerInterface $logger,
        UserWalletServiceInterface $userWalletService,
        WithdrawServiceInterface $withdrawService,
        ProfitGraphServiceInterface $profitGraphService,
        GxGlobalConfig $gxGlobalConfig,
        GxOrderServiceInterface $gxOrderService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->gxOrderService = $gxOrderService;
        $this->gxConfig = $gxGlobalConfig;
        $this->profitGraphService = $profitGraphService;
        $this->withdrawService = $withdrawService;
        $this->logger = $logger;
        $this->userWalletService = $userWalletService;
    }

    /**
     * 查询订单
     * @param PagingParams $pagingParams
     * @param int $payStatus
     * @param string $mobile
     * @param string $orderNo
     * @return CallResult|string
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams, $payStatus = 0, $username = '', $orderNo = '') {
        $this->checkLogin();
        $map = [];

        if ($payStatus != 2) {
            $map['pay_status'] = intval($payStatus);
        }
        if (!empty($orderNo)) {
            $map['order_no'] = ['like', '%'.$orderNo.'%'];
        }
        if (!empty($username)) {
            $ua = $this->userAccountService->info(['username' => $username, 'project_id' => $this->getProjectId()]);
            if ($ua instanceof UserAccount) {
                $map['uid'] = $ua->getId();
            }
        }

        return $this->gxOrderService->queryAndCount($map, $pagingParams, ["id" => "desc"]);
    }

    public function export($startTime, $endTime) {
        $startTime = intval($startTime);
        $endTime = intval($endTime);
        if ($endTime < $startTime) {
            $tmp = $endTime;
            $endTime = $startTime;
            $startTime = $tmp;
        }

        $map = [
            'pay_status' => GxOrder::Paid
        ];

        $map['create_time'] = ['gte', $startTime, 'lt', $endTime];

        $list = $this->gxOrderService->queryAllBy($map);

        $sheetData = [
            [
                '订单号',
                '金额',
                '到账金额',
                '支付通道',
                '支付通道方交易号',
                '支付时间',
                '手续费',
                '备注'
            ]
        ];

        foreach ($list as $vo) {
            array_push($sheetData, [
                $vo['order_no'],
                $vo['amount'],
                $vo['arrival_amount'],
                (new PayWayConst($vo['pw']))->__toString(),
                $vo['pay_ret_order_id'],
                date("Y-m-d H:i:s", $vo['paid_time']),
                $vo['fee'],
                $vo['remark']
            ]);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = date('Y-m-d', $startTime).'至'.date("Y-m-d", $endTime)."的支付订单列表";
        $sheet->setTitle($title);

//        $columns = [
//            'order_no' => '订单号',
//            'amount' => '金额',
//            'arrival_amount' => '到账金额',
//            'pw' => '支付通道',
//            'pay_ret_order_id' => '支付通道方交易号',
//            'paid_time' => '支付时间',
//            'fee' => '手续费',
//            'remark' => '备注'
//        ];
        $sheet->getStyle("A2:H1")->getAlignment()->setWrapText(true);

        $sheet->fromArray($sheetData, null, "A2");
        $sheet->getStyle('F2:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
//        $sheet->getStyle('F1:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension("A")->setWidth(40);
        $sheet->getColumnDimension("B")->setWidth(16);
        $sheet->getColumnDimension("C")->setWidth(16);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("H")->setWidth(120);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->setCellValue('A1', $title);

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = md5(time()).rand(1000, 9999).'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }


    public function statics($startTime, $endTime) {
        $startTime = intval($startTime);
        $endTime = intval($endTime);
        if ($endTime < $startTime) {
            $tmp = $endTime;
            $endTime = $startTime;
            $startTime = $tmp;
        }
        $map = [
            'pay_status' => GxOrder::Paid
        ];
        if ($startTime > 0) {
            $map['create_time'] = ['gte', $startTime, 'lt', $endTime];
        }
        $gxOrderAmount = $this->gxOrderService->sum($map, "amount");

        $map = [
            'audit_status' => AuditStatus::Passed
        ];
        if ($startTime > 0) {
            $map['create_time'] = ['gte', $startTime, 'lt', $endTime];
        }
        $withdrawAmount = $this->withdrawService->sum($map, "amount");

        return CallResultHelper::success([
            'gx_order_amount' => empty($gxOrderAmount) ? "0" : $gxOrderAmount,
            'withdraw_amount' => StringHelper::numberFormat($withdrawAmount / 100)
        ]);
    }


    public function repair($orderNo, $outTradeNo) {
        $gxOrder = $this->gxOrderService->info(['order_no' => $orderNo]);
        if (!$gxOrder instanceof GxOrder) {
            $this->logger->error('[订单号不存在]');
            return 'out_order_id not exists';
        }

        if ($gxOrder->getPayStatus() != GxOrder::PayInitial) {
            $this->logger->error('[支付回调] 已处理订单' . $gxOrder->getOrderNo());
            return 'already processed';
        }

        $wallet = $this->userWalletService->info(['uid' => $gxOrder->getUid()]);
        if (!$wallet instanceof UserWallet) {
            $this->logger->error('用户' . $gxOrder->getUid() . '的钱包不存在');
            return '用户' . $gxOrder->getUid() . '的钱包不存在';
        }

        $this->gxOrderService->getEntityManager()->beginTransaction();
        try {
            $this->gxOrderService->findById($gxOrder->getId(), LockMode::PESSIMISTIC_READ);
            if ($gxOrder->getPayStatus() == GxOrder::Paid) {
                $this->gxOrderService->getEntityManager()->rollback();
                $this->logger->error('[支付回调] 订单已处理' . $gxOrder->getOrderNo());
                return 'already processed';
            }
            $gxOrder->setPayStatus(GxOrder::Paid);
            $gxOrder->setPaidTime(time());
            $gxOrder->setArrivalAmount(StringHelper::numberFormat($gxOrder->getAmount() / 100, 2));
            $gxOrder->setSign('');
            $gxOrder->setPayRetOrderId($outTradeNo);
            $gxOrder->setMerchantCode('');
            $gxOrder->setPw(PayWayConst::PW002);
            $gxOrder->setRemark($gxOrder->getRemark().'[补单]');

            $note = '充值了' . $gxOrder->getAmount() . '元';
            $this->userWalletService->deposit($wallet->getId(), $gxOrder->getAmount() * 100, $note);

            $note = '购买VIP' . $gxOrder->getVipItemId() . '支出了' . ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) . '元';
            $this->userWalletService->withdraw($wallet->getId(), ($gxOrder->getAmount() - $gxOrder->getExtraAmount()) * 100, $note);


            $this->gxOrderService->flush($gxOrder);
            $this->gxOrderService->getEntityManager()->commit();
        } catch (Exception $exception) {
            $this->gxOrderService->getEntityManager()->rollback();
            $this->logger->error('[支付回调] 更新订单信息失败' . $exception->getMessage());
            return '更新订单信息失败' . $exception->getMessage();
        }
        $ret = $this->paySuccess($gxOrder);
        if ($ret->isFail()) {
            // 只记录这个
            $this->logger->error('[支付回调] 处理订单失败' . $ret->getMsg());
        }

        return CallResultHelper::success();
    }

    /**
     * @param GxOrder $gxOrder
     * @return CallResult
     * @throws Exception
     */
    protected function paySuccess(GxOrder $gxOrder)
    {
        $this->gxConfig->init($gxOrder->getProjectId());
        // 完成支付后依据订单类型进行分类处理
        if ($gxOrder->getVipItemId() === 1) {
            // vip1
            $ret = $this->profitGraphService->upgradeToVip1($gxOrder->getId(), $gxOrder->getUid(), $this->gxConfig);
        } else {
            // vip2 - vip9
            $ret = $this->profitGraphService->upgradeToVipN($gxOrder->getId(), $gxOrder->getUid());
        }
        return $ret;
    }
}
