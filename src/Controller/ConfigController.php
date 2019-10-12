<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/19
 * Time: 11:37
 */

namespace App\Controller;


use App\Entity\Config;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\config\ConfigParser;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

class ConfigController extends BaseNeedLoginController
{

    /**
     * @var ConfigServiceInterface
     */
    protected $configService;

    public function __construct(
        ConfigServiceInterface $configService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->configService = $configService;
    }


    /**
     * 创建
     * @param $cate
     * @param $title
     * @param $name
     * @param $cfgType
     * @param $value
     * @param string $sort
     * @param string $remark
     * @param string $extra
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function create($cate, $title, $name, $cfgType, $value, $sort = '', $remark = '', $extra = '')
    {
        $this->checkLogin();
        if (!Config::isValidType($cfgType)) {
            return CallResultHelper::fail(["%param% invalid", ['%param%' => 'cfgType']]);
        }

        $cfg = new Config();
        $cfg->setTitle($title);
        $cfg->setCate($cate);
        $cfg->setName($name);
        $cfg->setType($cfgType);
        $cfg->setValue($value);
        $cfg->setExtra($extra);
        $cfg->setSort($sort);
        $cfg->setRemark($remark);
        $cfg->setProjectId($this->getProjectId());
        $cfg->setStatus(StatusEnum::ENABLE);
        $ret = $this->configService->add($cfg);
        return $ret;
    }

    /**
     * @param $id
     * @param $cate
     * @param $title
     * @param $name
     * @param $cfgType
     * @param $value
     * @param string $sort
     * @param string $remark
     * @param string $extra
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function update($id, $cate, $title, $name, $cfgType, $value, $sort = '', $remark = '', $extra = '')
    {
        $this->checkLogin();
        $cfg = $this->configService->info(['id' => $id]);
        if ($cfg instanceof Config) {
            $cfg->setCate($cate);
            $cfg->setTitle($title);
            $cfg->setName($name);
            $cfg->setType($cfgType);
            $cfg->setValue($value);
            $cfg->setSort($sort);
            $cfg->setRemark($remark);
            $cfg->setExtra($extra);

            $this->configService->flush($cfg);
        }

        return $cfg;
    }

    /**
     * 查询短信配置信息
     * @return mixed
     */
    public function querySmsConfig()
    {
        return $this->configService->queryAllBy(['status' => StatusEnum::ENABLE, 'project_id' => $this->getProjectId(), 'name' => ['like', 'code_sms_%']]);
    }

    /**
     * 返回分类信息
     * @return \by\infrastructure\base\CallResult
     */
    public function cate()
    {
        $info = $this->configService->info(['status' => StatusEnum::ENABLE, 'name' => 'CONFIG_GROUP_LIST', 'project_id' => $this->getProjectId()]);
        if ($info instanceof Config) {
            return CallResultHelper::success(ConfigParser::parse($info->getType(), $info->getValue()));
        }
        return CallResultHelper::success([]);
    }

    /**
     * 查询配置信息
     * @param $cate
     * @param PagingParams $pagingParams
     * @param string $name
     * @return \by\infrastructure\base\CallResult
     */
    public function query($cate, PagingParams $pagingParams, $name = '')
    {
        $map = ['cate' => $cate, 'status' => StatusEnum::ENABLE, 'project_id' => $this->getProjectId()];
        if (!empty($name)) {
            $map['name'] = ['like', '%' . $name . '%'];
        }
        return $this->configService->queryAndCount($map, $pagingParams);
    }

    /**
     * 删除配置 - 根据id
     * @param $id
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $config = $this->configService->info(['id' => $id, 'project_id' => $this->getProjectId()]);
        if ($config instanceof Config) {
            $config->setName('DEL_' . time() . '_' . $config->getName());
            $config->setStatus(StatusEnum::SOFT_DELETE);
            $this->configService->flush($config);
        }
        return CallResultHelper::success();
    }

    /**
     * @param $uProjectId
     * @return string|Response
     * @throws \Exception
     */
    public function init($uProjectId)
    {
        return $this->configService->initByProjectId($uProjectId);
    }

    /**
     * 获取当前请求环境下projectId对应的配置版本号
     * @return \by\infrastructure\base\CallResult
     */
    public function version() {
        $config = $this->configService->info(['status' => StatusEnum::ENABLE, 'project_id' => $this->getProjectId(), 'name' => 'CONFIG_VERSION']);
        if (!$config instanceof Config) {
            return CallResultHelper::success(10000);
        }

        return CallResultHelper::success($config->getValue());
    }

    /**
     * 查询当前请求环境下的所有配置
     * @return mixed
     */
    public function queryAll() {
        return $this->configService->queryAllBy(['status' => StatusEnum::ENABLE, 'project_id' => $this->getProjectId()]);
    }
}
