<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2019-05-29
 * Time: 17:51
 */

namespace byTest\component\zmf_pay;


use App\Entity\AuthRole;
use App\Repository\AuthRoleRepository;
use App\Service\AuthRoleService;
use Doctrine\ORM\EntityManager;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\ItemInterface;

class CacheTest extends WebTestCase
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    public function testIndex()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        $cache = $container->get('cache.app');
        $this->assertInstanceOf(CacheItemPoolInterface::class, $cache);


        if ($cache instanceof CacheItemPoolInterface) {
            $cacheItem = $cache->getItem('333_test');
            if ($cacheItem->isHit()) {
                var_dump($cacheItem->get());
            } else {
                $cacheItem->set('cache_value');
                $cache->save($cacheItem);
            }

            var_dump($cacheItem->getKey());
        }
    }

    public function testSerialize() {
        $roles = $this->entityManager->getRepository(AuthRole::class)
            ->findAll();
        $count = count($roles);
        $serializeRoles = serialize($roles);
        var_dump($serializeRoles);

        $container = self::$kernel->getContainer();
        $cache = $container->get('cache.app');
        if ($cache instanceof CacheItemPoolInterface) {
            $cacheItem = $cache->getItem('serialize_test');
            if ($cacheItem->isHit()) {
                var_dump($cacheItem->get());
            } else {
                $cacheItem->set($serializeRoles);
                $cache->save($cacheItem);
            }
            var_dump($cacheItem->getKey());
            $cacheItem = $cache->getItem('serialize_test');
            if ($cacheItem->isHit()) {
                $serializeRoles = $cacheItem->get();
                $roles = unserialize($serializeRoles);
                $this->assertEquals($count, count($roles), '长度变化了');
                $this->assertInstanceOf(AuthRole::class, ($roles[0]), '不是AuthRole类');
            }
        }

        $this->assertNotEmpty($serializeRoles);
    }
}
