<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_tags", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_pinyin", columns={"pinyin"})})
 * @ORM\Entity(repositoryClass="App\Repository\TagsRepository")
 */
class Tags extends BaseEntity
{

    public function __construct()
    {
        parent::__construct();
        $this->videos = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->cmsArticles = new ArrayCollection();
    }

    public function getVideos() {
        return $this->videos;
    }
    /**
     * Many Tags have Many Albums.
     * @ORM\ManyToMany(targetEntity="Video", mappedBy="tags")
     **/
    private $videos;

    public function getAlbums() {
        return $this->albums;
    }
    /**
     * Many Tags have Many Albums.
     * @ORM\ManyToMany(targetEntity="Album", mappedBy="tags")
     **/
    private $albums;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $title;

    /**
     * @ORM\Column(type="bigint")
     */
    private $createTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $pinyin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CmsArticle", mappedBy="tags")
     */
    private $cmsArticles;

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

    public function getPinyin(): ?string
    {
        return $this->pinyin;
    }

    public function setPinyin(string $pinyin): self
    {
        $this->pinyin = $pinyin;

        return $this;
    }

    /**
     * @return Collection|CmsArticle[]
     */
    public function getCmsArticles(): Collection
    {
        return $this->cmsArticles;
    }

    public function addCmsArticle(CmsArticle $cmsArticle): self
    {
        if (!$this->cmsArticles->contains($cmsArticle)) {
            $this->cmsArticles[] = $cmsArticle;
            $cmsArticle->addTag($this);
        }

        return $this;
    }

    public function removeCmsArticle(CmsArticle $cmsArticle): self
    {
        if ($this->cmsArticles->contains($cmsArticle)) {
            $this->cmsArticles->removeElement($cmsArticle);
            $cmsArticle->removeTag($this);
        }

        return $this;
    }
}
