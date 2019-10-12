<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/19
 * Time: 11:37
 */

namespace App\AdminController;


use App\Entity\Config;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\config\ConfigParser;
use by\component\paging\vo\PagingParams;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class ConfigController extends BaseSymfonyApiController
{

    /**
     * @var ConfigServiceInterface
     */
    protected $configService;

    public function __construct(KernelInterface $kernel, ConfigServiceInterface $configService)
    {
        $this->configService = $configService;
        parent::__construct($kernel);
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($cate, $title, $name, $cfgType, $value, $sort = '', $remark = '', $extra = '')
    {
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
     */
    public function update($id, $cate, $title, $name, $cfgType, $value, $sort = '', $remark = '', $extra = '')
    {
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
        $info = $this->configService->info(['name' => 'CONFIG_GROUP_LIST', 'project_id' => $this->getProjectId()]);
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
        $map = ['cate' => $cate, 'status' => StatusEnum::ENABLE];
        if (!empty($name)) {
            $map['name'] = ['like', '%'.$name.'%'];
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
            $config->setName('DEL_' . time() . '_'.$config->getName());
            $config->setStatus(StatusEnum::SOFT_DELETE);
            $this->configService->flush($config);
        }
        return CallResultHelper::success();
    }
}
