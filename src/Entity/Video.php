<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video extends BaseEntity
{
    /**
     * Many Album have Many Tags.
     * @ORM\ManyToMany(targetEntity="Tags")
     * @ORM\JoinTable(name="video_tags",
     *      joinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     **/
    private $tags;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $description;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $cateId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uploaderId;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $uploadNick;

    /**
     * @ORM\Column(type="smallint")
     */
    private $showStatus;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $cover;

    /**
     * @ORM\Column(type="bigint")
     */
    private $views;

    /**
     * @ORM\Column(type="bigint")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $recommend;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $actors;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $directors;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $language;

    /**
     * @ORM\Column(type="smallint")
     */
    private $end;

    /**
     * Video constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->showStatus = 0;
        $this->year = date("Y");
        $this->tags = new ArrayCollection();
        $this->setLanguage('');
        $this->setDirectors('');
        $this->setActors('');
        $this->setArea('');
        $this->setEnd(0);
    }

    public function getTags() {
//        return $this->tags;
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

    public function getCateId(): ?int
    {
        return $this->cateId;
    }

    public function setCateId(int $cateId): self
    {
        $this->cateId = $cateId;

        return $this;
    }

    public function getUploaderId(): ?int
    {
        return $this->uploaderId;
    }

    public function setUploaderId(int $uploaderId): self
    {
        $this->uploaderId = $uploaderId;

        return $this;
    }

    public function getUploadNick(): ?string
    {
        return $this->uploadNick;
    }

    public function setUploadNick(string $uploadNick): self
    {
        $this->uploadNick = $uploadNick;

        return $this;
    }

    public function getShowStatus(): ?int
    {
        return $this->showStatus;
    }

    public function setShowStatus(int $showStatus): self
    {
        $this->showStatus = $showStatus;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getRecommend(): ?int
    {
        return $this->recommend;
    }

    public function setRecommend(int $recommend): self
    {
        $this->recommend = $recommend;

        return $this;
    }

    public function getActors(): ?string
    {
        return $this->actors;
    }

    public function setActors(string $actors): self
    {
        $this->actors = $actors;

        return $this;
    }

    public function getDirectors(): ?string
    {
        return $this->directors;
    }

    public function setDirectors(string $directors): self
    {
        $this->directors = $directors;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getEnd(): ?int
    {
        return $this->end;
    }

    public function setEnd(int $end): self
    {
        $this->end = $end;

        return $this;
    }
}
