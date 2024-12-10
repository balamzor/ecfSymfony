<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debutAbonnement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $finAbonnement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebutAbonnement(): ?\DateTimeInterface
    {
        return $this->debutAbonnement;
    }

    public function setDebutAbonnement(\DateTimeInterface $debutAbonnement): static
    {
        $this->debutAbonnement = $debutAbonnement;

        return $this;
    }

    public function getFinAbonnement(): ?\DateTimeInterface
    {
        return $this->finAbonnement;
    }

    public function setFinAbonnement(\DateTimeInterface $finAbonnement): static
    {
        $this->finAbonnement = $finAbonnement;

        return $this;
    }
}
