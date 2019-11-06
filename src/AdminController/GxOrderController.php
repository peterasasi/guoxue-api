<?php


namespace App\AdminController;


use App\Common\GxGlobalConfig;
use App\Common\PayWayConst;
use App\Entity\GxOrder;
use App\Entity\ProfitGraph;
use App\Entity\UserAccount;
use App\Helper\CodeGenerator;
use App\ServiceInterface\GxOrderServiceInterface;
use App\ServiceInterface\ProfitGraphServiceInterface;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;

class GxOrderController extends BaseNeedLoginController
{
    protected $gxOrderService;
    protected $gxConfig;
    protected $profitGraphService;

    public function __construct(
        ProfitGraphServiceInterface $profitGraphService,
        GxGlobalConfig $gxGlobalConfig,
        GxOrderServiceInterface $gxOrderService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->gxOrderService = $gxOrderService;
        $this->gxConfig = $gxGlobalConfig;
        $this->profitGraphService = $profitGraphService;
    }

    /**
     * 查询订单
     * @param PagingParams $pagingParams
     * @param string $mobile
     * @param string $orderNo
     * @return CallResult|string
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams, $mobile = '', $orderNo = '') {
        $this->checkLogin();
        $map = [];
        if (!empty($orderNo)) {
            $map['order_no'] = ['like', '%'.$orderNo.'%'];
        }
        if (!empty($mobile)) {
            $ua = $this->userAccountService->info(['mobile' => $mobile]);
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
        $sheet->getStyle("A1:H1")->getAlignment()->setWrapText(true);

        $sheet->fromArray($sheetData, null, "B1");
        $sheet->getStyle('F2:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
//        $sheet->getStyle('F1:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension("A")->setWidth(40);
        $sheet->getColumnDimension("B")->setWidth(16);
        $sheet->getColumnDimension("C")->setWidth(16);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("H")->setWidth(120);
        $sheet->mergeCells('A1:H1');
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
}
