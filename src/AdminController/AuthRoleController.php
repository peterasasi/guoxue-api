<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 16:56
 */

namespace App\AdminController;


use App\Entity\AuthPolicy;
use App\Entity\AuthRole;
use App\Entity\Menu;
use App\Entity\UserAccount;
use App\ServiceInterface\AuthPolicyServiceInterface;
use App\ServiceInterface\AuthRoleServiceInterface;

use App\ServiceInterface\MenuServiceInterface;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Common\UserLogServiceInterface;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\HttpKernel\KernelInterface;

class AuthRoleController extends BaseNeedLoginController
{
    /**
     * @var AuthRoleServiceInterface
     */
    protected $roleService;

    /**
     * @var AuthPolicyServiceInterface
     */
    protected $policyService;

    /**
     * @var UserAccountServiceInterface
     */
    protected $userService;

    /**
     * @var MenuServiceInterface
     */
    protected $menuService;

    /**
     * @var UserLogServiceInterface
     */
    protected $userLogService;


    /**
     * AuthRoleController constructor.
     * @param LoginSessionInterface $loginSession
     * @param UserLogServiceInterface $logService
     * @param MenuServiceInterface $menuService
     * @param UserAccountServiceInterface $userAccountService
     * @param AuthPolicyServiceInterface $policyService
     * @param AuthRoleServiceInterface $roleService
     * @param KernelInterface $kernel
     */
    public function __construct(UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, UserLogServiceInterface $logService, MenuServiceInterface $menuService, AuthPolicyServiceInterface $policyService, AuthRoleServiceInterface $roleService, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->menuService = $menuService;
        $this->roleService = $roleService;
        $this->policyService = $policyService;
        $this->userService = $userAccountService;
        $this->userLogService = $logService;
    }

    /**
     * @param $id
     * @param $name
     * @param string|bool $note
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $name = '', $note = false) {
        $this->checkLogin();
        $role = $this->roleService->info(['id' => $id]);
        if ($role instanceof AuthRole) {
            $logInfo = '';
            if (!empty($name) && $role->getName() != $name) {
                $logInfo .= 'Update Record Id = '.$id.' Set Name '.$role->getName().' To '.$name;
                $role->setName($name);
            }
            if ($note !== false && $role->getNote() != $note) {
                $logInfo .= ' Update Record Id = '.$id.' Change Note';
                $role->setNote(strval($note));
            }
            $this->roleService->flush($role);
            if (!empty($logInfo)) {
                $this->logUserAction($this->userLogService, $logInfo);
            }
        }
        return CallResultHelper::success();
    }

    /**
     * @param $name
     * @param $note
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($name, $note)
    {
        $entity = new AuthRole();
        $entity->setNote($note);
        $entity->setName($name);
        $entity->setEnable(StatusEnum::ENABLE);
        $role = $this->roleService->add($entity);
        $logInfo = 'Create Role '.$role->getId();
        if (!empty($logInfo)) {
            $this->logUserAction($this->userLogService, $logInfo);
        }
        return CallResultHelper::success([
            'id' => $role->getId(),
            'name' => $role->getName(),
            'note' => $role->getNote()
        ]);
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \by\component\exception\NotLoginException
     */
    public function info($id)
    {
        $this->checkLogin();
        $role = $this->roleService->info(['id' => $id]);
        if (!$role instanceof AuthRole) {
            return 'name invalid';
        }
        return CallResultHelper::success([
            'id' => $role->getId(),
            'name' => $role->getName(),
            'note' => $role->getNote(),
            'policies' => $role->getPolicies()
        ]);
    }

    /**
     * @param PagingParams $pagingParams
     * @param $name
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $name = '')
    {
        $map = [];

        if (!empty($name)) {
            $map['name'] = ['like', '%' . $name . '%'];
        }

        $list = $this->roleService->queryBy($map, $pagingParams, ["id" => 'asc']);
        $count = $this->roleService->count($map);
        return CallResultHelper::success([
            'list' => $list,
            'count' => $count
        ]);
    }

    /**
     * @param $id
     * @param $status
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeStatus($id, $status)
    {
        if ($id == 1) return CallResultHelper::fail();

        if (intval($status) === StatusEnum::ENABLE) {
            $logInfo = 'Enable Role '.$id;
            $this->roleService->updateOne(['id' => $id], ['enable' => StatusEnum::ENABLE]);
        } elseif (intval($status) === StatusEnum::DISABLED) {
            $logInfo = 'Disable Role '.$id;
            $this->roleService->updateOne(['id' => $id], ['enable' => StatusEnum::DISABLED]);
        }

        if (!empty($logInfo)) {
            $this->logUserAction($this->userLogService, $logInfo);
        }
        return CallResultHelper::success();
    }


    /**
     * @param $id
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function delete($id)
    {
        $this->checkLogin();
        try {
            $logInfo = 'Delete Role ' . $id;
            if (!empty($logInfo)) {
                $this->logUserAction($this->userLogService, $logInfo);
            }
            return $this->roleService->deleteWhere(['id' => $id]);
        } catch (ConstraintViolationException $exception) {
            return CallResultHelper::fail('删除失败');
        }
    }


    /**
     * 附加用户
     * @param $userId
     * @param $roleId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function attachUser($userId, $roleId)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        $user = $this->userService->info(['id' => $userId]);

        if (!($role instanceof AuthRole) || !($user instanceof UserAccount)) {
            return "record not exist";
        }
        $user->addRole($role);
        $this->userService->add($user);

        $logInfo = 'User '.$userId. ' Attach Role '.$role->getId();
        if (!empty($logInfo)) {
            $this->logUserAction($this->userLogService, $logInfo);
        }
        return "success";
    }

    /**
     * 移除用户
     * @param $userId
     * @param $roleId
     * @return AuthRole|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeUser($userId, $roleId)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        $user = $this->userService->info(['id' => $userId]);
        if (!($role instanceof AuthRole) || !($user instanceof UserAccount)) {
            return "record not exist";
        }
        $user->removeRole($role);
        $logInfo = 'User '.$userId. ' Remove Role '.$role->getId();
        if (!empty($logInfo)) {
            $this->logUserAction($this->userLogService, $logInfo);
        }
        return $this->userService->flush($user);
    }

    /**
     * 查询用户所有的角色
     * @param $userId
     * @return mixed|string
     */
    public function queryByUid($userId)
    {
        $user = $this->userService->info(['id' => $userId]);
        if (!($user instanceof UserAccount)) {
            return "record not exist";
        }
        return $user->getRoles()->map(function ($role) {
            if ($role instanceof AuthRole) {
                return [
                    'id' => $role->getId(),
                    'name' => $role->getName(),
                    'note' => $role->getNote(),
                ];
            }
            return [];
        });
    }

    /**
     * 查询用户所有的角色
     * @param $roleId
     * @param PagingParams $pagingParams
     * @param string $mobile
     * @return mixed|string
     */
    public function listUsers($roleId, PagingParams $pagingParams, $mobile = '')
    {
        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        $criteria = Criteria::create()
            ->orderBy(array("id" => Criteria::ASC))
            ->setFirstResult($pagingParams->getPageIndex() * $pagingParams->getPageSize())
            ->setMaxResults($pagingParams->getPageSize());

        if (!empty($mobile)) {
            $cp = new Comparison('mobile', 'like', new Value('%'.$mobile.'%'));
            $criteria->where($cp);
            $count = $role->users->matching(Criteria::create()->where($cp))->count();
        } else {
            $count = $role->users->count();
        }

        $users = $role->users->matching($criteria);

        $arr = [];
        foreach ($users as $user) {
            if ($user instanceof UserAccount) {
                array_push($arr, [
                    'id' => $user->getId(),
                    'mobile' => $user->getMobile(),
                    'email' => $user->getEmail(),
                    'email_auth' => $user->isEmailAuth(),
                    'mobile_auth' => $user->isMobileAuth(),
                    'nickname' => $user->getProfile()->getNickname(),
                    'avatar' => $user->getProfile()->getHead(),
                    'country_no' => $user->getCountryNo()
                ]);
            }
        }
        return CallResultHelper::success([
            'count' => $count,
            'list' => $arr
        ]);
    }

    // role and menu

    /**
     * @param $roleId
     * @param $menuIds
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function attachMenus($roleId, $menuIds)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        $idArr = explode(",", $menuIds);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }

        foreach ($idArr as $id) {
            if ($id) {
                $menu = $this->menuService->info(['id' => $id]);
                if ($menu instanceof Menu) {
                    $role->addMenu($menu);
                }
            }
        }
        $this->roleService->flush($role);
        return 'success';
    }

    /**
     * @param $roleId
     * @param $menuIds
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeMenus($roleId, $menuIds) {
        $idArr = explode(",", $menuIds);
        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        foreach ($idArr as $menuId) {
            if ($menuId) {
                $menu = $this->menuService->info(['id' => $menuId]);
                if ($menu instanceof Menu) {
                    $role->removeMenu($menu);
                }
            }
        }
        $this->roleService->flush($role);
        return CallResultHelper::success();
    }

    /**
     * @param $roleId
     * @param $policyIds
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function attachPolicies($roleId, $policyIds)
    {

        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        $idArr = explode(",", $policyIds);

        foreach ($idArr as $policyId) {
            if ($policyId) {
                $policy = $this->policyService->info(['id' => $policyId]);
                if ($policy instanceof AuthPolicy) {
                    $role->addPolicy($policy);
                }
            }
        }
        $this->roleService->flush($role);
        return "success";
    }

    /**
     * @param $roleId
     * @param $policyIds
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removePolicies($roleId, $policyIds)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        $idArr = explode(",", $policyIds);
        foreach ($idArr as $policyId) {
            if ($policyId) {
                $policy = $this->policyService->info(['id' => $policyId]);

                if ($policy instanceof AuthPolicy) {
                    $role->removePolicy($policy);
                }
            }
        }
        $this->roleService->flush($role);
        return CallResultHelper::success();
    }

    public function listPolicies($roleId)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        $policies = $role->getPolicies()->filter(function ($item) {
            return $item instanceof AuthPolicy && $item->getisDefaultVersion() == 1;
        });
        $formatPolicies = [];
        foreach ($policies as $item) {
            if ($item instanceof AuthPolicy) {
                array_push($formatPolicies,
                    [
                        'id' => $item->getId(),
                        'label' => $item->getName().'['.$item->getNote().']',
                    ]);
            }
        }

        $allPolicies = $this->policyService->queryAllBy(['is_default_version' => 1]);
        $formatAllPolicies = [];

        foreach ($allPolicies as $vo) {
            array_push($formatAllPolicies, [
                'id' => $vo['id'],
                'label' => $vo['name'].'['.$vo['note'].']',
            ]);
        }

        return [$formatPolicies, $formatAllPolicies];
    }

    /**
     * 查询角色关联菜单并按格式分组
     * @param $roleId
     * @return []
     */
    public function listMenus($roleId)
    {
        $role = $this->roleService->info(['id' => $roleId]);
        if (!($role instanceof AuthRole)) {
            return "record not exist";
        }
        $menu = $role->getMenus()->filter(function ($item) {
            return $item instanceof Menu && $item->getStatus() == StatusEnum::ENABLE;
        });
        $formatMenu = [];
        foreach ($menu as $item) {
            if ($item instanceof Menu) {
                array_push($formatMenu, $item->getId());
            }
        }

        $allMenu = $this->menuService->queryAllBy(['status' => StatusEnum::ENABLE]);
        $formatAllMenu = $this->convertToTree($allMenu);

        return [$formatMenu, $formatAllMenu];
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
            } else {
                $id = $item['id'];
                $pid = $item['pid'];
                $title = $item['title'];
                $level = $item['level'];
            }
            $pKey = 'K' . $pid;
            $key = 'K' . $id;
            if ($level == 0) {
                // 一级菜单
                $formatMenu[$key] = [
                    'id' => $id,
                    'label' => $title,
                    'children' => []
                ];
            } elseif ($level == 1) {
                // 二级菜单
                if (array_key_exists($pKey, $formatMenu)) {
                    $formatMenu[$pKey]['children'][$key] = [
                        'id' => $id,
                        'label' => $title,
                        'children' => []
                    ];
                }
            } elseif ($level == 2) {
                foreach ($formatMenu as $k => $subMenu) {
                    if (array_key_exists($pKey, $subMenu['children'])) {
                        array_push($formatMenu[$k]['children'][$pKey]['children'], [
                            'id' => $id,
                            'label' => $title,
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

}
