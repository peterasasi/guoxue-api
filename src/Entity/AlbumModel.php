<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumModelRepository")
 */
class AlbumModel
{
    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="albumModel")
     */
    private $albums;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $description;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    /**
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
}
