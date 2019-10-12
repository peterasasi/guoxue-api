<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="common_country")
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $telPrefix;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $py;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTelPrefix(): ?string
    {
        return $this->telPrefix;
    }

    public function setTelPrefix(string $telPrefix): self
    {
        $this->telPrefix = $telPrefix;

        return $this;
    }

    public function getPy(): ?string
    {
        return $this->py;
    }

    public function setPy(string $py): self
    {
        $this->py = $py;

        return $this;
    }
}
