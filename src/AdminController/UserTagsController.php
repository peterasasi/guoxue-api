<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/12/24
 * Time: 11:31
 */

namespace App\AdminController;


use App\Entity\UserTags;
use App\ServiceInterface\UserTagsServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class UserTagsController extends BaseSymfonyApiController
{

    /**
     * @var UserTagsServiceInterface
     */
    protected $service;

    public function __construct(UserTagsServiceInterface $service, KernelInterface $kernel)
    {
        $this->service = $service;
        parent::__construct($kernel);
    }

    public function query($uid) {
        return $this->service->queryAllBy(['uid' => $uid]);
    }

    /**
     * @param $id
     * @param $tagName
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $tagName) {
        $info = $this->service->info(['id' => $id]);
        if ($info instanceof UserTags) {
            if ($info->getTagName() != $tagName) {
                $info->setTagName($tagName);
                $this->service->flush($info);
            }
        }
        return $info;
    }

    /**
     * @param $uid
     * @param string $tagName
     * @param int $whoTagUid
     * @return UserTags|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($uid, $tagName = '', $whoTagUid = 0) {
        $map = ['uid' => $uid, 'tag_name' => $tagName];
        $tag = $this->service->info($map);
        if ($tag instanceof UserTags) {
            return $tag;
        }
        $tag = new UserTags();
        $tag->setUid($uid);
        $tag->setWhoTagUid($whoTagUid);
        $tag->setTagName($tagName);
        return $this->service->add($tag);
    }

    /**
     * @param $id
     * @return int
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $map = ['id' => $id];
        $tag = $this->service->info($map);
        if ($tag instanceof UserTags) {
            return $this->service->delete($tag);
        }
        return 0;
    }

}
