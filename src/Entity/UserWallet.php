<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserWalletRepository")
 */
class UserWallet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $uid;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $balance;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $frozen;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $withdrawTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getFrozen()
    {
        return $this->frozen;
    }

    /**
     * @param mixed $frozen
     */
    public function setFrozen($frozen): void
    {
        $this->frozen = $frozen;
    }

    public function getWithdrawTotal()
    {
        return $this->withdrawTotal;
    }

    public function setWithdrawTotal($withdrawTotal): self
    {
        $this->withdrawTotal = $withdrawTotal;

        return $this;
    }
}
