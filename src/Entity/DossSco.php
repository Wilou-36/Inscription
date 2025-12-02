<?php

namespace App\Entity;

use App\Repository\DossScoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossScoRepository::class)]
class DossSco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idSco = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $regimeSco = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $specialite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_scolar', referencedColumnName: 'id_scolar', nullable: false)]
    private ?Scolarite $scolarite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'annee', referencedColumnName: 'annee', nullable: true)]
    private ?AnneeSco $anneeSco = null;

    public function getIdSco(): ?int
    {
        return $this->idSco;
    }

    public function getRegimeSco(): ?string
    {
        return $this->regimeSco;
    }

    public function setRegimeSco(?string $regimeSco): static
    {
        $this->regimeSco = $regimeSco;
        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getScolarite(): ?Scolarite
    {
        return $this->scolarite;
    }

    public function setScolarite(?Scolarite $scolarite): static
    {
        $this->scolarite = $scolarite;
        return $this;
    }

    public function getAnneeSco(): ?AnneeSco
    {
        return $this->anneeSco;
    }

    public function setAnneeSco(?AnneeSco $anneeSco): static
    {
        $this->anneeSco = $anneeSco;
        return $this;
    }
}