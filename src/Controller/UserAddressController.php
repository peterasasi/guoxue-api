<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Controller;

use App\Entity\UserAddress;

use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use App\ServiceInterface\UserAddressServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class UserAddressController extends BaseNeedLoginController
{
    /**
     * @var UserAddressServiceInterface
     */
    protected $userAddressService;

    public function __construct(UserAccountServiceInterface $userAccountService, UserAddressServiceInterface $userAddressService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->userAddressService = $userAddressService;
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \by\component\exception\NotLoginException
     */
    public function query($userId)
    {
        $this->checkLogin();
        return $this->userAddressService->queryAllBy(['uid' => $userId], ['id' => 'desc']);
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $this->checkLogin();
        $userAddress = $this->userAddressService->info(['id' => $id]);
        if (!($userAddress instanceof UserAddress)) {
            return CallResultHelper::fail('id invalid');
        }
        $this->userAddressService->delete($userAddress);
        return CallResultHelper::success();
    }

    /**
     * @param $userId
     * @param $provinceCode
     * @param $cityCode
     * @param $cityAreaCode
     * @param $townCode
     * @param $contactMobile
     * @param $name
     * @param $detail
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($provinceCode, $cityCode, $cityAreaCode, $townCode, $contactMobile, $name, $detail)
    {

        $this->checkLogin();

        $provinceName = $this->request->get('province_name', '');
        $cityName = $this->request->get('city_name', '');
        $cityAreaName = $this->request->get('city_area_name', '');
        $townName = $this->request->get('town_name', '');
        $isDefault = $this->request->get('is_default', 0);
        $userAddress = new UserAddress();
        $userAddress->setDetail($detail);
        $userAddress->setName($name);
        $userAddress->setUid(intval($this->getUid()));
        $userAddress->setProvinceCode($provinceCode);
        $userAddress->setProvinceName($provinceName);
        $userAddress->setTownCode($townCode);
        $userAddress->setTownName($townName);
        $userAddress->setCityCode($cityCode);
        $userAddress->setCityName($cityName);
        $userAddress->setCityAreaCode($cityAreaCode);
        $userAddress->setCityAreaName($cityAreaName);
        $userAddress->setContactMobile($contactMobile);
        $userAddress->setIsDefault(intval($isDefault));

        if ($userAddress->getIsDefault() == 1) {
            $defaultUserAddress = $this->userAddressService->info(['uid' => $this->getUid(), 'is_default' => 1]);
            if ($defaultUserAddress instanceof UserAddress) {
                $defaultUserAddress->setIsDefault(0);
                $this->userAddressService->flush($defaultUserAddress);
            }
        }

        $this->userAddressService->add($userAddress);
        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @param $provinceCode
     * @param $cityCode
     * @param $cityAreaCode
     * @param $townCode
     * @param $contactMobile
     * @param $name
     * @param $detail
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\exception\NotLoginException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $provinceCode, $cityCode, $cityAreaCode, $townCode, $contactMobile, $name, $detail)
    {

        $this->checkLogin();

        $provinceName = $this->request->get('province_name', '');
        $cityName = $this->request->get('city_name', '');
        $cityAreaName = $this->request->get('city_area_name', '');
        $townName = $this->request->get('town_name', '');
        $isDefault = $this->request->get('is_default', 0);
        $userAddress = $this->userAddressService->info(['uid' => $this->getUid(), 'id' => $id]);
        if (!($userAddress instanceof UserAddress)) {
            return CallResultHelper::fail('id invalid');
        }
        $isDefault = intval($isDefault);

        $userAddress->setDetail($detail);
        $userAddress->setName($name);
        $userAddress->setProvinceCode($provinceCode);
        $userAddress->setProvinceName($provinceName);
        $userAddress->setTownCode($townCode);
        $userAddress->setTownName($townName);
        $userAddress->setCityCode($cityCode);
        $userAddress->setCityName($cityName);
        $userAddress->setCityAreaCode($cityAreaCode);
        $userAddress->setCityAreaName($cityAreaName);
        $userAddress->setContactMobile($contactMobile);
        if ($isDefault != $userAddress->getIsDefault()) {
            $userAddress->setIsDefault($isDefault);
            if ($userAddress->getIsDefault() == 1) {
                $defaultUserAddress = $this->userAddressService->info(['uid' => $userAddress->getUid(), 'is_default' => 1]);
                if ($defaultUserAddress instanceof UserAddress) {
                    $defaultUserAddress->setIsDefault(0);
                    $this->userAddressService->flush($defaultUserAddress);
                }
            }
        }

        $this->userAddressService->flush($userAddress);
        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function info($id)
    {
        return $this->userAddressService->info(['id' => $id]);
    }
}
