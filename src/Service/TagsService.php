<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace App\Service;


use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\ServiceInterface\TagsServiceInterface;
use Dbh\SfCoreBundle\Common\BaseService;


class TagsService extends BaseService implements TagsServiceInterface
{
    /**
     * @var TagsRepository
     */
    protected $repo;

    public function __construct(TagsRepository $tagsRepository)
    {
        $this->repo = $tagsRepository;
    }


    /**
     * @param Tags $tags
     * @return array|mixed|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNotExists(Tags $tags)
    {
        $map = [
            'pinyin' => $tags->getPinyin()
        ];
        $info = $this->info($map);
        if ($info instanceof Tags) {
            return $info;
        }

        return $this->add($tags);
    }

}
