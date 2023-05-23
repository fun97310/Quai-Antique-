<?php

namespace App\Entity;

use App\Repository\JoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoursRepository::class)]
class Jours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $jour = null;

    #[ORM\ManyToOne(inversedBy: 'jours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HeureMatins $h_matin = null;

    #[ORM\ManyToOne(inversedBy: 'jours')]
    private ?HeureSoirs $h_soir = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHMatin(): ?HeureMatins
    {
        return $this->h_matin;
    }

    public function setHMatin(?HeureMatins $h_matin): self
    {
        $this->h_matin = $h_matin;

        return $this;
    }

    public function getHSoir(): ?HeureSoirs
    {
        return $this->h_soir;
    }

    public function setHSoir(?HeureSoirs $h_soir): self
    {
        $this->h_soir = $h_soir;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }
}
