<?php


namespace App\Controller;


use App\Entity\UserAccount;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class GoogleAuthController extends BaseNeedLoginController
{
    public function qrcode($size = 600) {
        $this->checkLogin();
        $auth = new \App\Helper\GoogleAuth();
        $user = $this->getUser();
        if (!$user instanceof UserAccount) {
            return 'failed';
        }

        if (empty($user->getGoogleSecret())) {
            return CallResultHelper::success('');
        }

        $url = $auth->getQRCodeGoogleUrl($user->getId(), $user->getGoogleSecret(), 'DBH');

        $qrCode = new QrCode(urldecode($url));

        $qrCode->setSize($size);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        return CallResultHelper::success('data:'.$qrCode->getContentType().';base64,'.(base64_encode($qrCode->writeString())));
    }
}
