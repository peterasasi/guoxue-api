<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 15:39
 */

namespace App\AdminController;


use App\Entity\AuthPolicy;
use App\ServiceInterface\AuthPolicyServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\ram\PolicyType;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class AuthPolicyController extends BaseSymfonyApiController
{
    /**
     * @var AuthPolicyServiceInterface
     */
    protected $service;

    public function __construct(AuthPolicyServiceInterface $service, KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->service = $service;
    }

    /**
     *
     * @param $id
     * @param string $name
     * @param string $statements
     * @param string $note
     * @param string $cate
     * @param int $ver
     * @param int $isDefaultVersion
     * @return mixed|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $name = '', $statements = '', $note = '', $cate = PolicyType::System, $ver = 1, $isDefaultVersion = 0) {
        $info = $this->service->info(['id' => $id]);
        if ($info instanceof AuthPolicy) {
            if ($info->getNote() != $note) {
                $info->setNote($note);
            }
            if ($info->getName() != $name) {
                $info->setName($name);
            }
            if ($info->getStatements() != $statements) {
                $ret = $this->isValidStatement($statements);
                if ($ret !== true) {
                    return CallResultHelper::fail($ret);
                }
                $info->setStatements(json_encode(json_decode($statements, JSON_OBJECT_AS_ARRAY)));
            }
            if ($info->getVer() != $ver) {
                $info->setVer($ver);
            }
            if ($info->getCate() != $cate) {
                $info->setCate($cate);
            }
            if ($info->getisDefaultVersion() != $isDefaultVersion) {
                $info->setIsDefaultVersion($isDefaultVersion);
                if ($isDefaultVersion) {
                    $this->service->updateWhere(['name' => $name], ['isDefaultVersion' => 0]);
                }
            }
            $this->service->flush($info);
            return $info;
        }
        return "record not exist";
    }

    protected function isValidStatement($statements) {
        $statements = json_decode($statements, JSON_OBJECT_AS_ARRAY);
        if ($statements === false || !is_array($statements)) return ["%param% invalid", ["%param%" => "statements"]];
        if (count($statements) > 10) return "too many statements";
        if (count($statements) == 0) return ["%param% invalid", ["%param%" => "statements"]];
        foreach ($statements as $vo) {
            if (!array_key_exists('Effect', $vo)) {
                return ["%param% lack", ["%param%" => "Effect"]];
            }
            if (!array_key_exists('Action', $vo)) {
                return ["%param% lack", ["%param%" => "Action"]];
            }
            if (!array_key_exists('Resource', $vo)) {
                return ["%param% lack", ["%param%" => "Resource"]];
            }
        }
        return true;
    }

    /**
     * 创建
     * @param $name
     * @param $statements
     * @param string $note
     * @param string $cate
     * @param int $ver
     * @param int $isDefaultVersion
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($name, $statements, $note = '', $cate = PolicyType::System, $ver = 1, $isDefaultVersion = 1) {
        $ret = $this->isValidStatement($statements);
        if ($ret !== true) {
            return CallResultHelper::fail($ret);
        }
        $entity = new AuthPolicy();
        $entity->setVer($ver);
        $entity->setStatements(json_encode(json_decode($statements, JSON_OBJECT_AS_ARRAY)));
        $entity->setName($name);
        $entity->setCate($cate);
        $entity->setNote($note);

        if ($isDefaultVersion) {
            $this->service->updateWhere(['name' => $name], ['isDefaultVersion' => 0]);
        }
        $entity->setIsDefaultVersion($isDefaultVersion);
        return $this->service->add($entity);
    }

    /**
     * 查询
     * @param PagingParams $pagingParams
     * @param string $name
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $name = '') {
        $map = [
            'name' => ['like', '%'.$name.'%']
        ];
        return $this->service->queryBy($map, $pagingParams, ["id" => 'desc']);
    }

    public function queryAndCount(PagingParams $pagingParams, $name = '') {
        $map = [];
        if (!empty($name)) {
            $map['name'] = ['like', '%' . $name . '%'];
        }
        return $this->service->queryAndCount($map, $pagingParams, ["id" => 'desc']);
    }
}
