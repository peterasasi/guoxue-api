<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;


use App\Entity\Menu;
use App\ServiceInterface\MenuServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class MenuController extends BaseSymfonyApiController
{

    protected $menuService;

    public function __construct(MenuServiceInterface $menuService, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->menuService = $menuService;
    }

    public function info($id)
    {
        return $this->menuService->info(['id' => $id]);
    }

    public function queryAll()
    {
        $list = $this->menuService->queryAllBy(['status' => StatusEnum::ENABLE, 'scene' => Menu::FrontMenu], ['id' => 'asc']);
        return CallResultHelper::success($this->convertToTree($list));
    }

    protected function convertToTree($menu)
    {
        $formatMenu = [];
        foreach ($menu as $item) {

            if ($item instanceof Menu) {
                $id = $item->getId();
                $pid = $item->getPid();
                $title = $item->getTitle();
                $level = $item->getLevel();
                $url = $item->getUrl();
            } else {
                $id = $item['id'];
                $pid = $item['pid'];
                $title = $item['title'];
                $level = $item['level'];
                $url = $item['url'];
            }
            $pKey = 'K' . $pid;
            $key = 'K' . $id;
            if ($level == 0) {
                // 一级菜单
                $formatMenu[$key] = [
                    'id' => $id,
                    'label' => $title,
                    'url' => $url,
                    'children' => []
                ];
            } elseif ($level == 1) {
                // 二级菜单
                if (array_key_exists($pKey, $formatMenu)) {
                    $formatMenu[$pKey]['children'][$key] = [
                        'id' => $id,
                        'label' => $title,
                        'url' => $url,
                        'children' => []
                    ];
                }
            } elseif ($level == 2) {
                foreach ($formatMenu as $k => $subMenu) {
                    if (array_key_exists($pKey, $subMenu['children'])) {
                        array_push($formatMenu[$k]['children'][$pKey]['children'], [
                            'id' => $id,
                            'label' => $title,
                            'url' => $url,
                        ]);
                    }
                }
            }
        }
        $formatMenu = array_values($formatMenu);
        foreach ($formatMenu as &$vo) {
            $vo['children'] = array_values($vo['children']);
        }

        return $formatMenu;
    }

    public function query($pid, PagingParams $pagingParams)
    {
        return $this->menuService->queryAndCount(['pid' => $pid, 'scene' => Menu::FrontMenu, 'status' => StatusEnum::ENABLE], $pagingParams, ['sort' => 'desc']);
    }

    /**
     * @param $id
     * @param $title
     * @param $urlType
     * @param string $url
     * @param string $icon
     * @param int $hide
     * @param string $tip
     * @param int $sort
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $urlType, $url = '', $icon = '', $hide = 0, $tip = '', $sort = 0)
    {
        $menu = $this->menuService->info(['id' => $id]);
        if ($menu instanceof Menu) {
            if ($menu->getTip() != $tip) {
                $menu->setTip($tip);
            }
            if ($menu->getUrlType() != $urlType) {
                $menu->setUrlType($urlType);
            }
            if ($menu->getSort() != $sort) {
                $menu->setSort($sort);
            }
            if ($menu->getTitle() != $title) {
                $menu->setTitle($title);
            }
            if ($menu->getUrl() != $url) {
                $menu->setUrl($url);
            }
            if ($menu->getIcon() != $icon) {
                $menu->setIcon($icon);
            }
            if ($menu->getHide() != $hide) {
                $menu->setHide($hide);
            }
            $this->menuService->flush($menu);

            return $menu;
        }
        return $menu;
    }

    /**
     * create menu
     * @param $title
     * @param $scene
     * @param string $url
     * @param string $icon
     * @param int $hide
     * @param string $tip
     * @param int $sort
     * @param int $pid
     * @return mixed
     */
    public function create($title, $scene, $url = "#", $icon = '', $hide = 0, $tip = '', $sort = 0, $pid = 0)
    {
        $menu = new Menu();
        if ($scene != Menu::BackendMenu && $scene != Menu::FrontMenu) {
            return CallResultHelper::fail('scene invalid');
        }
        $menu->setScene($scene);
        $pid = intval($pid);
        $menu->setTip($tip ?? '');
        $menu->setTitle($title);
        $menu->setUrl($url);
        $menu->setIcon($icon);
        $menu->setHide($hide ?? 0);
        $menu->setSort($sort);
        $menu->setPid($pid);
        $menu->setStatus(StatusEnum::ENABLE);
        $menu->setUrlType(1);
        if ($pid > 0) {
            $parentMenu = $this->menuService->info(['id' => $pid]);
            if ($parentMenu instanceof Menu) {
                $menu->setLevel(intval($parentMenu->getLevel()) + 1);
            }
        } else {
            $menu->setLevel(0);
        }
        return $this->menuService->add($menu);
    }

    /**
     * @param $id
     * @return null|object|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $cnt = $this->menuService->count(['pid' => $id, 'status' => StatusEnum::ENABLE]);
        if ($cnt > 0) {
            return "delete deny";
        }
        $this->menuService->updateOne(['id' => $id], ['status' => StatusEnum::SOFT_DELETE]);
        return 0;
    }
}
