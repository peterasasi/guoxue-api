<?php

namespace App\Controller;

use App\Common\CacheKeys;
use App\Entity\Config;
use App\Entity\SecurityCode;
use App\Helper\CodeImageHelper;
use App\Message\EmailCodeMsg;
use App\Service\SecurityCodeService;
use App\ServiceInterface\ConfigServiceInterface;
use by\component\config\ConfigParser;
use by\component\message_sender\constants\MessageSenderTypeEnum;
use by\component\message_sender\facade\MessageSenderFacade;
use by\component\message_sender\impl\AlertMessageSender;
use by\component\message_sender\interfaces\SenderInterface;
use by\component\paging\vo\PagingParams;
use by\component\security_code\constants\SecurityCodeType;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use by\infrastructure\helper\Object2DataArrayHelper;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

/**
 * 验证码控制器
 * Class SecurityCodeController
 * @package App\Controller
 */
class SecurityCodeController extends BaseSymfonyApiController
{

    /**
     * @var SecurityCodeService
     */
    protected $selfService;

    /**
     * @var ConfigServiceInterface
     */
    protected $configService;

    protected $cache;

    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        KernelInterface $kernel, ConfigServiceInterface $configService, SecurityCodeService $securityCodeService)
    {
        $this->selfService = $securityCodeService;
        $this->configService = $configService;
        $this->cache = $cacheItemPool;
        parent::__construct($kernel);
    }

    /**
     * 查询
     * @param string $accepter
     * @param int $status
     * @param PagingParams $pagingParams
     * @return mixed
     */
    public function query(PagingParams $pagingParams, $accepter = '', $status = 0)
    {
        $map = [];
        if (!empty($accepter)) {
            $map['accepter'] = $this->getAccepter($accepter);
        }
        if (in_array($status, [0, 1])) {
            $map['status'] = $status;
        }
        return $this->selfService->queryBy($map, $pagingParams);
    }

    private function getAccepter($accepter)
    {
        return $this->getProjectId() . '_' . $accepter;
    }

    /**
     * 图形验证码 base64形式
     * @param $accepter
     * @param string $codeType
     * @param int $codeLength
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createImage($accepter, $codeType = '', $codeLength = 4)
    {
        $entity = $this->create($accepter, $codeType, $codeLength);
        $img = (new CodeImageHelper())->entry($entity->getCode());
        ob_start();
        imagepng($img);
        $image_data = ob_get_contents();
        $image_data_base64 = "data:image/png;base64," . base64_encode($image_data);
        ob_end_clean();
        $data = [
            'id' => $entity->getId(),
            'code' => $image_data_base64
        ];
        return $data;
    }

    /**
     * 创建验证码 过期时间固定 5分钟
     * @param $accepter
     * @param string $codeType
     * @param int $codeLength
     * @param int $codeRandType
     * @return SecurityCode
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($accepter, $codeType = '', $codeLength = 4, $codeRandType = StringHelper::ALPHABET, $checkLimit = false)
    {
        $ip = ip2long($this->request->getClientIp());
//        if ($checkLimit) {
//            $map = [
//                'accepter' => $accepter,
//                'ip' => $ip,
//                'type' => $codeType,
//                'create_time' => ['gt', BY_APP_START_TIMESTAMP - 3600]// 60分钟内
//            ];
//            $cnt = $this->selfService->count($map);
//            if ($cnt > 120) {
//
//            }
//        }
        $entity = new SecurityCode();
        $entity->setAccepter($this->getAccepter($accepter));
        $entity->setClientId($this->getClientId());
        $entity->setCode(StringHelper::randStr($codeRandType, $codeLength));
        $entity->setIp($ip);
        $entity->setCreateTime(time());
        $entity->setUpdateTime(time());
        $entity->setStatus(0);
        $entity->setType(intval($codeType));
        $entity->setExpiredTime(time() + 300);
        $this->selfService->add($entity);
        return $entity;
    }

    /**
     * 检测该场景下验证码是否有效
     * @param $accepter
     * @param $codeType
     * @param $code
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function check($accepter, $codeType, $code)
    {
        return $this->selfService->isLegalCode($code, $this->getAccepter($accepter), $codeType, $this->getClientId(), true);
    }

    /**
     * TODO: 加一个验证码作为验证，防止频繁发送短信
     * @param $accepter
     * @param $codeType
     * @param ValidatorInterface $validator
     * @param int $codeLength
     * @param string $countryNo
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function createAndSend($accepter, $codeType, ValidatorInterface $validator, $codeLength = 6, $countryNo = '')
    {

        $entity = $this->create($countryNo . '_' . $accepter, $codeType, $codeLength, StringHelper::NUMBERS);

        $data = [
            'project_id' => $this->getProjectId(),
            'scene' => SecurityCodeType::getTypeDesc($entity->getType()),
            'code' => $entity->getCode(),
            'mobile' => $accepter,
            'country_no' => $countryNo
        ];

        if ($this->ifEmail($accepter, $validator)) {
            $this->dispatchMessage(new EmailCodeMsg($this->getProjectId(), $accepter, $entity->getCode()));
            return CallResultHelper::success("email had sent. please check your email account after some seconds");
        } elseif ($this->ifMobile($accepter)) {
            // TODO: 判断上一次发送的时间间隔
            // 短信配置进行缓存
            $cacheConfig = $this->cache->getItem(CacheKeys::SmsConfig);
            if ($cacheConfig->isHit()) {
                $cfg = json_decode($cacheConfig->get(), JSON_OBJECT_AS_ARRAY);
            } else {
                $list = $this->querySmsConfig();
                if (count($list) == 0) return "please set up SMS configuration information.";
                $configEntity = new Config();
                Object2DataArrayHelper::setData($configEntity, $list[0]);
                $name = $configEntity->getName();
                $cfg = [
                    'name' => $name,
                    'type' => $configEntity->getType(),
                    'value' => $configEntity->getValue()
                ];
                $cacheConfig->set(json_encode($cfg, JSON_UNESCAPED_UNICODE));
                $cacheConfig->expiresAfter(CacheKeys::getExpireTime(CacheKeys::SmsConfig));
                $this->cache->save($cacheConfig);
            }

            $value = ConfigParser::parse($cfg['type'], $cfg['value']);
            if (is_array($value)) {
                $data = array_merge($value, $data);
            }

            $name = $cfg['name'];

            $name = str_replace('code_', '', $name);
            $result = MessageSenderFacade::create($name, $data);
            if ($result instanceof SenderInterface) {
                return $result->send();
            }

            return "send sms failed, please check your sms config";
        } else {
            if ($this->kernel->isDebug()) {
                return MessageSenderFacade::create(MessageSenderTypeEnum::ALERT, $data)->send();
            }
        }


        return "accepter must be a valid mobile or email address";
    }

    public function ifEmail($accepter, ValidatorInterface $validator)
    {
        $email = new Email([
            'mode' => Email::VALIDATION_MODE_STRICT,
        ]);
        return $validator->validate($accepter, $email)->count() == 0;
    }

    public function ifMobile($accepter)
    {
        $match = [];
        preg_match("/^\d{5,15}$/", $accepter, $match);
        return count($match) > 0;
    }

    public function querySmsConfig()
    {
        return $this->configService->queryAllBy(['status' => StatusEnum::ENABLE, 'projectId' => $this->getProjectId(), 'name' => ['like', 'code_sms_%']], ['sort'=>'desc']);
    }

}
