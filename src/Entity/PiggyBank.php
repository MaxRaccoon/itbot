<?php

namespace App\Entity;

use App\Repository\PiggyBankRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiggyBankRepository::class)]
class PiggyBank
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private float $amount = 0.0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chatId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function addAmount(float $amount): self
    {
        $this->amount += $amount;
        return $this;
    }

    public function subtractAmount(float $amount): self
    {
        $this->amount -= $amount;
        return $this;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function setChatId(?string $chatId): self
    {
        $this->chatId = $chatId;

        return $this;
    }
}
