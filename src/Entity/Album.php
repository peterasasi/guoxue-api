<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="album")
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album extends BaseEntity
{

    /**
     * One Album have Many Photos.
     * @ORM\OneToMany(targetEntity="AlbumPhoto", mappedBy="album", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     **/
    public $photos;
    /**
     * Many Album have Many Tags.
     * @ORM\ManyToMany(targetEntity="Tags")
     * @ORM\JoinTable(name="album_tags",
     *      joinColumns={@ORM\JoinColumn(name="album_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     **/
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="AlbumCategory", inversedBy="albums")
     * @var AlbumCategory
     */
    private $cate;

    /**
     * @ORM\ManyToOne(targetEntity="AlbumModel", inversedBy="albums")
     * @var AlbumModel
     */
    private $albumModel;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;
    /**
     * @ORM\Column(type="integer")
     */
    private $total;
    /**
     * @ORM\Column(type="integer")
     */
    private $views;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $source;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $sourceKey;
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $description;
    /**
     * @ORM\Column(name="create_time", type="bigint")
     */
    private $createTime;
    /**
     * @ORM\Column(name="update_time", type="bigint")
     */
    private $updateTime;
    /**
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSourceKey()
    {
        return $this->sourceKey;
    }

    /**
     * @param mixed $sourceKey
     */
    public function setSourceKey($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    /**
     * @return mixed
     */
    public function getAlbumModel()
    {
        if (is_null($this->albumModel)) {
            return [
                'id' => 0,
                'title' => '',
                'description' => ''
            ];
        }
        return [
            'id' => $this->albumModel->getId(),
            'name' => $this->albumModel->getName(),
            'description' => $this->albumModel->getDescription()
        ];
    }

    /**
     * @param mixed $albumModel
     */
    public function setAlbumModel($albumModel)
    {
        $this->albumModel = $albumModel;
    }

    /**
     * @return mixed
     */
    public function getCate()
    {
        return [
            'id' => $this->cate->getId(),
            'title' => $this->cate->getTitle()
        ];
    }

    /**
     * @param mixed $cate
     */
    public function setCate($cate)
    {
        $this->cate = $cate;
    }

    public function removePhoto(AlbumPhoto $albumPhoto) {
        if (!$this->photos->contains($albumPhoto)) {
            $this->photos->removeElement($albumPhoto);
        }
        return $this;
    }

    public function addPhoto(AlbumPhoto $albumPhoto) {
        if (!$this->photos->contains($albumPhoto)) {
            $this->photos->add($albumPhoto);
        }
        return $this;
    }

    public function getTags() {
        return array_map(
            function ($tags) {
                if (!($tags instanceof Tags)) { return $tags; }
                return [
                    'title' => $tags->getTitle(),
                    'pinyin' => $tags->getPinyin(),
                    'id' => $tags->getId()
                ];
            },
            $this->tags->toArray()
        );
    }



    public function removeAllTags() {
        $this->tags->clear();
        return $this;
    }

    public function removeTags(Tags $tags) {
        if (!$this->tags->contains($tags)) {
            $this->tags->removeElement($tags);
        }
        return $this;
    }

    public function addTags(Tags $tags) {
        if (!$this->tags->contains($tags)) {
            $this->tags->add($tags);
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getUpdateTime(): ?int
    {
        return $this->updateTime;
    }

    public function setUpdateTime(int $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getPhotos() {
        $criteria = Criteria::create()
            ->orderBy(array("albumIndex" => Criteria::ASC))
            ->setFirstResult(1)
            ->setMaxResults(4);
        return $this->photos->matching($criteria)->toArray();
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->photos->matching(Criteria::create()->orderBy(['albumIndex' => 'ASC']))->first();
    }
}
