<?php

namespace App\Entity;

use App\Repository\HeureSoirsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeureSoirsRepository::class)]
class HeureSoirs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $h_ouverture = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $h_fermeture = null;

    #[ORM\OneToMany(mappedBy: 'h_soir', targetEntity: Jours::class)]
    private Collection $jours;

    #[ORM\Column(nullable: true)]
    private ?bool $is_close = null;

    public function __construct()
    {
        $this->jours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHOuverture(): ?\DateTimeInterface
    {
        return $this->h_ouverture;
    }

    public function setHOuverture(\DateTimeInterface $h_ouverture): self
    {
        $this->h_ouverture = $h_ouverture;

        return $this;
    }

    public function getHFermeture(): ?\DateTimeInterface
    {
        return $this->h_fermeture;
    }

    public function setHFermeture(\DateTimeInterface $h_fermeture): self
    {
        $this->h_fermeture = $h_fermeture;

        return $this;
    }

    /**
     * @return Collection<int, Jours>
     */
    public function getJours(): Collection
    {
        return $this->jours;
    }

    public function addJour(Jours $jour): self
    {
        if (!$this->jours->contains($jour)) {
            $this->jours->add($jour);
            $jour->setHSoir($this);
        }

        return $this;
    }

    public function removeJour(Jours $jour): self
    {
        if ($this->jours->removeElement($jour)) {
            // set the owning side to null (unless already changed)
            if ($jour->getHSoir() === $this) {
                $jour->setHSoir(null);
            }
        }

        return $this;
    }

    public function isIsClose(): ?bool
    {
        return $this->is_close;
    }

    public function setIsClose(?bool $is_close): self
    {
        $this->is_close = $is_close;

        return $this;
    }
}
