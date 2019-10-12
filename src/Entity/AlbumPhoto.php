<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumPhotoRepository")
 */
class AlbumPhoto extends BaseEntity
{

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $album;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $albumIndex;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $photoUri;

    /**
     * @return integer
     */
    public function getAlbumId()
    {
        if ($this->album instanceof Album) {
            return $this->album->getId();
        } else {
            return 0;
        }
    }

    /**
     * @param Album $album
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlbumIndex(): ?int
    {
        return $this->albumIndex;
    }

    public function setAlbumIndex(int $albumIndex): self
    {
        $this->albumIndex = $albumIndex;

        return $this;
    }

    public function getPhotoUri(): ?string
    {
        return $this->photoUri;
    }

    public function setPhotoUri(string $photoUri): self
    {
        $this->photoUri = $photoUri;

        return $this;
    }
}
