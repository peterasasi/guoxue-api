<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\ServiceInterface\PcaServiceInterface;
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
