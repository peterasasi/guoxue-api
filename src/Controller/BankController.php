<?php


namespace App\Controller;


use by\component\bank\BankCard;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class BankController extends BaseSymfonyApiController
{
    public function info($cardNo)
    {
        $ret = BankCard::info($cardNo);
        if ($ret->isFail()) return $ret;
        $data = $ret->getData();
        $data['local_bank_img'] = $this->getApiUrl() . '/assets/img/bank/' . $data['bank'] . '.png';
        return CallResultHelper::success($data);
    }

    public function getApiUrl()
    {
        return $this->request->getSchemeAndHttpHost();
    }
}
