<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\AdminController;


use App\ServiceInterface\PcaServiceInterface;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class PcaController extends BaseSymfonyApiController
{
    /**
     * @var PcaServiceInterface
     */
    protected $pcaService;

    public function __construct(PcaServiceInterface $pcaService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->pcaService = $pcaService;
    }

    public function query3Level() {
        $maxLevel = 3;
        $allList = [];
        $allList[0] = $this->pcaService->queryProvince();
        $allList[1] = $this->pcaService->queryCity('all');
        $allList[2] = $this->pcaService->queryCityArea('all');
//        $allList[3] = $this->pcaService->queryTown('all');

        $level = $maxLevel;
        while ($level - 2 >= 0) {
            foreach ($allList[$level-2] as &$level2) {
                $level2['children'] = [];
                foreach ($allList[$level - 1] as $level3) {
                    if ($level3['parent_code'] == $level2['code']) {
                        array_push($level2['children'], $level3);
                    }
                }
            }
            $level--;
        }

        return CallResultHelper::success($allList[0]);
    }

    public function queryProvince() {
        return $this->pcaService->queryProvince();
    }

    public function queryCity($code) {
        return $this->pcaService->queryCity($code);
    }

    public function queryCityArea($code) {
        return $this->pcaService->queryCityArea($code);
    }

    public function queryTown($code) {
        return $this->pcaService->queryTown($code);
    }
}
