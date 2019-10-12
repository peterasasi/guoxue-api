<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpPropertyValueRepository")
 */
class SpPropertyValue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpProperty", inversedBy="spPropertyValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prop;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProp(): ?SpProperty
    {
        return $this->prop;
    }

    public function setProp(?SpProperty $prop): self
    {
        $this->prop = $prop;

        return $this;
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
}
