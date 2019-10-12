<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/8/7
 * Time: 13:50
 */

namespace App\AdminController;

use App\Common\ByVersionCheck;
use App\Common\CacheKeys;
use App\Entity\AuthPolicy;
use App\Entity\AuthRole;
use App\Entity\Clients;
use App\Entity\UserAccount;
use App\ServiceInterface\ApiReqHisServiceInterface;
use by\component\exception\ForbidException;
use by\component\exception\InvalidArgumentException;
use by\component\exception\NotSupportVersionApiException;
use by\component\exception\UglyException;
use Dbh\SfCoreBundle\Common\ByCacheKeys;
use Dbh\SfCoreBundle\Common\ByEnv;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use by\component\ram\ByAuthContext;
use by\component\ram\ByStatement;
use by\component\string_extend\helper\StringHelper;
use by\infrastructure\constants\StatusEnum;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;

/**
 * @package App\Controller
 */
class IndexController extends BaseSymfonyApiController
{
    /**
     * FilesystemAdapter
     */
    protected $cache = null;

    /**
     * @var ApiReqHisServiceInterface
     */
    protected $apiLogger = null;

    /**
     * @var UserAccountServiceInterface
     */
    protected $userAccountService;

    protected $logger;

    public function __construct(CacheItemPoolInterface $cacheItemPool, LoggerInterface $logger, UserAccountServiceInterface $userAccountService, KernelInterface $kernel, ApiReqHisServiceInterface $apiLogger)
    {
        parent::__construct($kernel);
        $this->logger = $logger;
        $this->apiLogger = $apiLogger;
        $this->userAccountService = $userAccountService;
        $this->cache = $cacheItemPool;
    }

    /**
     * @Route("/base/admin", name="admin_entry", methods={"POST"})
     * @return Response
     * @throws ForbidException
     * @throws InvalidArgumentException
     * @throws NotSupportVersionApiException
     * @throws UglyException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \by\component\ram\ForbidException
     */
    public function index()
    {
        $serviceType = $this->getServiceType();
        $ver = $this->getServiceVersion();
        $this->logger->debug('entry index');
        ByVersionCheck::checkVersion($ver, $serviceType, $this->getVersionHistory());
        $this->logger->debug('version check passed');
        $this->checkAuthority($serviceType);
        $this->logger->debug('auth passed');
        $this->checkCallRate($serviceType);
        $this->logger->debug('api rate check passed');

        $api_type = preg_replace("/_/", "/", substr(trim($serviceType), 3), 1);
        $api_type = preg_split("/\//", $api_type);

        if (count($api_type) < 2) {
            throw new InvalidArgumentException("invalid service type");
        }

        $actionName = $api_type[1];
        $serviceName = $api_type[0];
        $params = $this->context->getDecryptData();
        $cacheKey = "k_" . $serviceName . '_' . $actionName;
        $cacheContent = $this->getCacheContent($cacheKey);
        if ($cacheContent !== false) {
            return new JsonResponse(json_encode($cacheContent[0]), 200, [], true);
        }
        // 处理参数key 支持下划线与驼峰
        foreach ($params as $key => $vo) {
            $params[StringHelper::toCamelCase($key)] = $vo;
        }

        $resp = $this->forward('App\\AdminController\\' . $serviceName . 'Controller::' . $actionName, $params);

        $this->setCacheContent($cacheKey, $resp->getContent(), $cacheContent[1]);

        return $resp;
    }

    /**
     *
     * @return array
     * @throws ForbidException
     * @throws InvalidArgumentException
     */
    protected function getStatements()
    {

        $user = $this->userAccountService->info(['id' => $this->getClientUid()]);
        if (!($user instanceof UserAccount)) {
            throw new InvalidArgumentException(["%param% invalid" => ["%param%" => "client_id"]]);
        }
        $roles = $user->getRoles()->filter(function ($item) {
            return $item instanceof AuthRole && $item->getEnable() == StatusEnum::ENABLE;
        });

        if ($roles->count() == 0) {
            throw new ForbidException();
        }

        $statements = [];
        foreach ($roles as $role) {
            if ($role instanceof AuthRole) {
                foreach ($role->getPolicies() as $policy) {
                    if ($policy instanceof AuthPolicy && !empty($policy->getStatements())) {
                        array_push($statements, $policy->getStatements());
                    }
                }
            }
        }
        return $statements;
    }

    /**
     * 检测api权限
     * @param $serviceType
     * @return void
     * @throws ForbidException
     * @throws InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \by\component\ram\ForbidException
     */
    public function checkAuthority($serviceType)
    {
        $context = new ByAuthContext($serviceType);
        $context->setGetParams($this->request->query->all());
        $context->setPostParams($this->request->request->all());
        $context->setUa($this->request->headers->has("User-Agent") ? $this->request->headers->get("User-Agent") : "");
        $context->setUid($this->getClientUid());
        $context->setClientIp($this->request->getClientIp());

        // *** 增加缓存 获取 Statements START *************************************************
        $cacheItem = $this->cache->getItem(ByCacheKeys::ApiIndexAuthPolices.$this->getClientUid());
        if ($cacheItem->isHit()) {
            $this->logger->debug('use cache statements');
            $statements = unserialize($cacheItem->get());
        } else {
            $this->logger->debug('use database statements');
            $statements = $this->getStatements();
            $cacheItem->set(serialize($statements));
            $cacheItem->expiresAfter(CacheKeys::getExpireTime(ByCacheKeys::ApiIndexAuthPolices));
            $this->cache->save($cacheItem);
        }
        // *** 获取 Statements END *************************************************

        $allowStatements = [];
        $denyStatements = [];
        // 2重循环进行处理
        foreach ($statements as $statement) {
            $stArr = json_decode($statement, JSON_OBJECT_AS_ARRAY);
            if (is_array($stArr)) {
                foreach ($stArr as $item) {
                    if (array_key_exists('Effect', $item)
                        && array_key_exists("Resource", $item)
                        && array_key_exists("Action", $item)) {
                        if ($item['Effect'] === "Allow") {
                            array_push($allowStatements, new ByStatement($item));
                        } elseif ($item['Effect'] === "Deny") {
                            array_push($denyStatements, new ByStatement($item));
                        }
                    }
                }
            } else {
                throw new InvalidArgumentException("invalid statements");
            }
        }

        if (count($allowStatements) == 0 && count($denyStatements) == 0) {
            throw new ForbidException();
        }
        $context->setAllowStatementArr($allowStatements);
        $context->setDenyStatementArr($denyStatements);
        $context->checkAuth();
    }

    /**
     * @param $serviceType
     * @throws InvalidArgumentException
     * @throws UglyException
     */
    public function checkCallRate($serviceType)
    {
        $clientId = $this->getClientId();
        if (!empty($clientId)) {
            $obj = $this->context->getClientInfo();
            if (!$obj instanceof Clients) throw new InvalidArgumentException('clientInfo must be Clients');
            $this->apiLogger->check($obj, $serviceType);
        } else {
            throw new UglyException("parameter `client_id` is lack");
        }
    }

    /**
     * @param $cacheKey
     * @return array|bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getCacheContent($cacheKey)
    {

        $cacheList = ByEnv::get("API_CACHE_LIST");
        $cacheList = explode(",", $cacheList);


        if ($this->cache->hasItem($cacheKey)) {
            $hasCache = false;
            foreach ($cacheList as $item) {
                if (strpos($item, $cacheKey) === 0) {
                    $hasCache = $item;
                    break;
                }
            }
            if ($hasCache === false) return false;
            $tmp = explode(":", $hasCache);
            $ttl = 30;
            if (count($tmp) > 1) {
                $ttl = $tmp[1];
            }
            $startTime = BY_APP_START_TIME;
            $mTime = microtime();
            $mTime = explode(' ', $mTime);
            $endTime = $mTime[1] + $mTime[0];
            $costTime = intval((floatval($endTime) - $startTime) * 100) . 'ms';
            $cacheContent = $this->cache->getItem($cacheKey);
            if (empty($cacheContent) || !$cacheContent->isHit()) return false;
            $cacheContent = $cacheContent->get();
            $cacheContent = json_decode($cacheContent, JSON_OBJECT_AS_ARRAY);
            $cacheContent['_start'] = $startTime;
            $cacheContent['_cost'] = $costTime;
            $cacheContent['_cache'] = 1;
            ksort($cacheContent);
            return [$cacheContent, $ttl];
        }
        return false;
    }

    /**
     * @param $cacheKey
     * @param $content
     * @param $ttl
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function setCacheContent($cacheKey, $content, $ttl)
    {
        $cacheList = ByEnv::get("API_CACHE_LIST");
        $cacheList = explode(",", $cacheList);
        foreach ($cacheList as $item) {
            if (strpos($item, $cacheKey) === 0) {
                $cacheItem = $this->cache->getItem($cacheKey);
                if ($cacheItem->isHit()) {
                    // 已经缓存 则不处理 略过
                    continue;
                }
                $cacheItem->set($content);
                $cacheItem->expiresAfter($ttl);
                $this->cache->save($cacheItem);
                return;
            }
        }
    }


    // 获取已弃用版本

    /**
     * 所有开放接口的版本历史记录，但只记录弃用，不支持，
     * 不记录支持的版本，避免编写繁琐
     * @return array
     */
    public function getVersionHistory()
    {
        return [
            'by_SecurityCode_query' => [
                '<100' => ['status' => 'not_support', 'msg' => '不支持版本101以下']
            ],
            'by_Video_create' => [
                '<101' => ['status' => 'not_support', 'msg' => '不支持101以下版本']
            ]
        ];
    }
}
