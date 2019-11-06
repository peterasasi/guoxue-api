<?php


namespace App\AdminController;


use App\ServiceInterface\WithdrawServiceInterface;
use by\component\audit_log\AuditStatus;
use by\component\exception\NotLoginException;
use by\component\paging\vo\PagingParams;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;

class WithdrawController extends BaseNeedLoginController
{
    protected $withdrawService;

    public function __construct(
        WithdrawServiceInterface $withdrawService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->withdrawService = $withdrawService;
    }

    public function export($startTime, $endTime) {
        $startTime = intval($startTime);
        $endTime = intval($endTime);
        if ($endTime < $startTime) {
            $tmp = $endTime;
            $endTime = $startTime;
            $startTime = $tmp;
        }

        $map['audit_status'] = AuditStatus::Passed;

        $map['create_time'] = ['gte', $startTime, 'lt', $endTime];

        $list = $this->withdrawService->queryAllBy($map);

        $sheetData = [
            [
                '提现用户手机号',
                '提现金额',
                '申请时间',
                '审核人昵称',
                '审核时间'
            ]
        ];

        foreach ($list as $vo) {
            array_push($sheetData, [
                $vo['uid'],
                $vo['amount'],
                $vo['audit_nick'],
                date('Y-m-d H:i:s', $vo['create_time']),
                date('Y-m-d H:i:s', $vo['update_time'])
            ]);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = date('Y-m-d', $startTime).'至'.date("Y-m-d", $endTime)."的提现订单列表";
        $sheet->setTitle($title);

        $sheet->getStyle("A2:E1")->getAlignment()->setWrapText(true);

        $sheet->fromArray($sheetData, null, "A2");
//        $sheet->getStyle('F2:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
//        $sheet->getStyle('F1:F'.count($sheetData))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension("A")->setWidth(20);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
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

    /**
     * @param PagingParams $pagingParams
     * @param int $auditStatus
     * @return mixed
     * @throws NotLoginException
     */
    public function query(PagingParams $pagingParams, $auditStatus = AuditStatus::Initial)  {
        $this->checkLogin();
        $map = [
            'uid' => $this->getUid()
        ];
        $map['audit_status'] = intval($auditStatus);
        return $this->withdrawService->queryAndCount($map, $pagingParams, ["createTime" => "desc"]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotLoginException
     */
    public function pass($id) {
        $this->checkLogin();
        return $this->withdrawService->pass($id, $this->getUid(), $this->getLoginUserNick());
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotLoginException
     */
    public function deny($id) {
        $this->checkLogin();
        return $this->withdrawService->deny($id, $this->getUid(), $this->getLoginUserNick());
    }
}
