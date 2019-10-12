<?php


namespace App\AdminController;


use App\Entity\VideoCate;
use App\ServiceInterface\VideoCateServiceInterface;
use App\ServiceInterface\VideoServiceInterface;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Dbh\SfCoreBundle\Controller\BaseSymfonyApiController;
use Symfony\Component\HttpKernel\KernelInterface;

class VideoCateController extends BaseNeedLoginController
{
    protected $videoCateService;
    protected $videoService;

    public function __construct(
        VideoServiceInterface $videoService, VideoCateServiceInterface $videoCateService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->videoCateService = $videoCateService;
        $this->videoService = $videoService;
    }


    /**
     * @return mixed
     */
    public function query() {
        return $this->videoCateService->queryAllBy([], ['sort' => 'desc']);
    }

    /**
     * @param $title
     * @param $description
     * @param int $sort
     * @return mixed|string
     */
    public function create($title, $description, $sort = 0) {
        $entity = $this->videoCateService->info(['title' => $title]);
        if ($entity instanceof VideoCate) return '标题已存在';

        $entity = new VideoCate();
        $entity->setTitle($title);
        $entity->setDescription($description);
        $entity->setSort($sort);

        return $this->videoCateService->add($entity);
    }

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $sort
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $title, $description, $sort) {


        $entity = $this->videoCateService->findById($id);
        if (!$entity instanceof VideoCate) return 'id 不存在';

        if (!empty($title) && $entity->getTitle() != $title) {
            $entity->setTitle($title);
        }
        $entity->setDescription($description);
        $entity->setSort($sort);

        $conflictEntity = $this->videoCateService->info(['title' => $title]);
        if ($conflictEntity instanceof VideoCate && $conflictEntity->getId() != $id) return '标题已存在';

        $this->videoCateService->flush($entity);
        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id) {
        $cnt = $this->videoService->count(['cate_id' => $id]);
        if ($cnt > 0) return '不能删除有视频关联的分类';

        $entity = $this->videoCateService->findById($id);
        if (!$entity instanceof VideoCate) return CallResultHelper::success();

        $this->videoCateService->delete($entity);

        return CallResultHelper::success();
    }

}
