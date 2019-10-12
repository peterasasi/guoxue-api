<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Entity\Picture;
use App\Repository\PictureRepository;
use App\ServiceInterface\PictureServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class PictureService extends BaseService implements PictureServiceInterface
{
    /**
     * @var PictureRepository
     */
    protected $repo;

    public function __construct(PictureRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param Picture $entity
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function safeInsert(Picture $entity)
    {
        $map = [
            'sha1' => $entity->getSha1(),
            'md5' => $entity->getMd5()
        ];

        $info  = $this->repo->findOneBy($map);
        if ($info instanceof Picture) {
            $entity->setSaveName($info->getSaveName());
            $entity->setRelativePath($info->getRelativePath());
            $entity->setSaveName($info->getSaveName());
        }
        return $this->repo->add($entity);
    }


}
