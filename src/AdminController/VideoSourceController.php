<?php


namespace App\AdminController;


use App\Common\ByDatatreeCode;
use App\Entity\Datatree;
use App\Entity\Video;
use App\Entity\VideoCate;
use App\Entity\VideoSource;
use App\ServiceInterface\DatatreeServiceInterface;
use App\ServiceInterface\VideoCateServiceInterface;
use App\ServiceInterface\VideoServiceInterface;
use App\ServiceInterface\VideoSourceServiceInterface;
use by\component\paging\vo\PagingParams;
use by\component\video\VideoType;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;
use Dbh\SfCoreBundle\Common\LoginSessionInterface;
use Dbh\SfCoreBundle\Common\UserAccountServiceInterface;
use Dbh\SfCoreBundle\Controller\BaseNeedLoginController;
use Symfony\Component\HttpKernel\KernelInterface;

class VideoSourceController extends BaseNeedLoginController
{
    protected $videoService;
    protected $videoSourceService;
    protected $datatreeService;

    public function __construct(
        DatatreeServiceInterface $datatreeService,
        VideoSourceServiceInterface $videoSourceService,
        VideoServiceInterface $videoService,
        UserAccountServiceInterface $userAccountService, LoginSessionInterface $loginSession, KernelInterface $kernel)
    {
        parent::__construct($userAccountService, $loginSession, $kernel);
        $this->videoService = $videoService;
        $this->videoSourceService = $videoSourceService;
        $this->datatreeService = $datatreeService;
    }

    /**
     * 查询所有来源
     * @return mixed
     */
    public function queryComeFrom() {
        $map = [
            'code' => ['like', ByDatatreeCode::VideoSourceComeFrom.'___']
        ];
        return $this->datatreeService->queryAllBy($map, ['sort' => 'desc'], ["id", "code", "name"]);
    }

    /**
     * @param $vid
     * @return mixed
     */
    public function query($vid) {
        $map = ['vid' => $vid, 'status' => StatusEnum::ENABLE];
        return $this->videoSourceService->queryAllBy($map, ['sort' => 'asc']);
    }

    /**
     * @param $vid
     * @param $comeFrom
     * @param $vType
     * @param $vUri
     * @param $title
     * @param int $sort
     * @return mixed|string
     * @throws \by\component\exception\NotLoginException
     */
    public function create($vid, $comeFrom, $vType, $vUri, $title, $sort = 0) {
        $this->checkLogin();

        if (!VideoType::isSupport($vType)) {
            return $vType." 视频源类型不支持";
        }

        $srcKey = md5($vUri);
        $checkEntity = $this->videoSourceService->info(['src_key' => $srcKey, 'come_from' => $comeFrom]);
        if ($checkEntity instanceof VideoSource) {
            return  '源标识已存在,请检查是否重复添加';
        }

        $dt = $this->datatreeService->info(['code' => $comeFrom]);

        if (!$dt instanceof Datatree) {
            return '来源未定义';
        }

        $entity = new VideoSource();
        $entity->setTitle($title);
        $entity->setComeFromAlias($dt->getName());
        $entity->setSrcKey($srcKey);
        $entity->setVid($vid);
        $entity->setSort($sort);
        $entity->setStatus(StatusEnum::ENABLE);
        $entity->setComeFrom($comeFrom);
        $entity->setVType($vType);
        $entity->setVUri($vUri);

        return $this->videoSourceService->add($entity);
    }

    /**
     * @param $id
     * @param $comeFrom
     * @param $vType
     * @param $vUri
     * @param $title
     * @param int $sort
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function update($id, $comeFrom, $vType, $vUri, $title, $sort = 0) {

        $this->checkLogin();

        $entity = $this->videoSourceService->findById($id);
        if (!$entity instanceof VideoSource) return 'id 不存在';
        $srcKey = md5($vUri);

        if ($entity->getSrcKey() != $srcKey) {
            $checkEntity = $this->videoSourceService->info(['src_key' => $srcKey, 'come_from' => $comeFrom]);
            if ($checkEntity instanceof VideoSource) {
                return  '源标识已存在,请检查是否重复添加';
            }
            $entity->setSrcKey($srcKey);
        }
        if ($entity->getComeFrom() != $comeFrom) {
            $dt = $this->datatreeService->info(['code' => $comeFrom]);
            if (!$dt instanceof Datatree) {
                return '来源未定义';
            }
            $entity->setComeFromAlias($dt->getName());
            $entity->setComeFrom($comeFrom);
        }
        $entity->setTitle($title);
        $entity->setVType($vType);
        $entity->setVUri($vUri);
        $entity->setSort($sort);

        $this->videoSourceService->flush($entity);
        return CallResultHelper::success();
    }

    /**
     * @param $id
     * @return \by\infrastructure\base\CallResult|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \by\component\exception\NotLoginException
     */
    public function delete($id) {

        $this->checkLogin();

        $entity = $this->videoSourceService->findById($id);
        if (!$entity instanceof VideoSource) return CallResultHelper::success();
        $entity->setStatus(StatusEnum::SOFT_DELETE);
        $this->videoSourceService->flush($entity);

        return CallResultHelper::success();
    }

}
