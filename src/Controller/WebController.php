<?php


namespace App\Controller;


use App\Common\ByDatatreeCode;
use App\ServiceInterface\BannersServiceInterface;

use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseH5Controller;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


class WebController extends BaseH5Controller
{
    protected $bannerService;

    public function __construct(
        BannersServiceInterface $bannerService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->bannerService = $bannerService;
    }

    /**
     * @Route("/web/detect", name="web_detect")
     *
     */
    public function detect() {

        return $this->render("web/detect.html.twig");
    }

    /**
     * @Route("/web/card", name="web_card")
     * @throws \by\component\exception\NotLoginException
     */
    public function card()
    {
        $this->checkLogin();
        $map = ['position' => ByDatatreeCode::Web_ApplyCard];
        $now = number_format(BY_APP_START_TIME, 0, ".", "");
        $map['start_time'] = ['lt', $now];
        $map['end_time'] = ['gt', $now];

        $banners = $this->bannerService->queryAllBy($map, ["sort" => "desc"]);
        return $this->render("web/card.html.twig", ['banners' => $banners]);
    }

    /**
     * @Route("/web/lend", name="web_lend")
     * @throws \by\component\exception\NotLoginException
     */
    public function lend()
    {
        $this->checkLogin();
        $map = ['position' => ByDatatreeCode::Web_Lend];
        $now = number_format(BY_APP_START_TIME, 0, ".", "");
        $map['start_time'] = ['lt', $now];
        $map['end_time'] = ['gt', $now];

        $banners = $this->bannerService->queryAllBy($map, ["sort" => "desc"]);
        return $this->render("web/lend.html.twig", ['banners' => $banners]);
    }


}
