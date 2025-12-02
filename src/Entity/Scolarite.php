<?php

namespace App\Entity;

use App\Repository\ScolariteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScolariteRepository::class)]
class Scolarite
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idScolar = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $classe = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lv1 = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lv2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $etablissement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $option = null;

    // Temporairement commenté pour éviter les problèmes DateTime
    // #[ORM\ManyToOne]
    // #[ORM\JoinColumn(name: 'annee', referencedColumnName: 'annee', nullable: true)]
    // private ?AnneeSco $annee = null;

    public function getIdScolar(): ?string
    {
        return $this->idScolar;
    }

    public function setIdScolar(string $idScolar): static
    {
        $this->idScolar = $idScolar;
        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): static
    {
        $this->classe = $classe;
        return $this;
    }

    public function getLv1(): ?string
    {
        return $this->lv1;
    }

    public function setLv1(?string $lv1): static
    {
        $this->lv1 = $lv1;
        return $this;
    }

    public function getLv2(): ?string
    {
        return $this->lv2;
    }

    public function setLv2(?string $lv2): static
    {
        $this->lv2 = $lv2;
        return $this;
    }

    public function getEtablissement(): ?string
    {
        return $this->etablissement;
    }

    public function setEtablissement(?string $etablissement): static
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getOption(): ?string
    {
        return $this->option;
    }

    public function setOption(?string $option): static
    {
        $this->option = $option;
        return $this;
    }

    // Temporairement commenté
    // public function getAnnee(): ?AnneeSco
    // {
    //     return $this->annee;
    // }

    // public function setAnnee(?AnneeSco $annee): static
    // {
    //     $this->annee = $annee;
    //     return $this;
    // }
}