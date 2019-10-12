<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\ServiceInterface;

use Dbh\SfCoreBundle\Common\BaseServiceInterface;
interface PcaServiceInterface extends BaseServiceInterface
{
    function queryProvince();

    function queryCity($provinceCode = '');

    function queryCityArea($cityCode = '');

    function queryTown($cityAreaCode = '');
}
