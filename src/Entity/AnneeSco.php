<?php

namespace App\Entity;

use App\Repository\AnneeScoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeScoRepository::class)]
class AnneeSco
{
    #[ORM\Id]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $annee = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $intitule = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOuverture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFermeture = null;

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): static
    {
        $this->annee = $annee;
        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): static
    {
        $this->intitule = $intitule;
        return $this;
    }

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->dateOuverture;
    }

    public function setDateOuverture(?\DateTimeInterface $dateOuverture): static
    {
        $this->dateOuverture = $dateOuverture;
        return $this;
    }

    public function getDateFermeture(): ?\DateTimeInterface
    {
        return $this->dateFermeture;
    }

    public function setDateFermeture(?\DateTimeInterface $dateFermeture): static
    {
        $this->dateFermeture = $dateFermeture;
        return $this;
    }

    public function isInscriptionOuverte(): bool
    {
        $now = new \DateTime();
        
        if (!$this->dateOuverture || !$this->dateFermeture) {
            return true; // Si pas de dates configurées, toujours ouvert
        }
        
        return $now >= $this->dateOuverture && $now <= $this->dateFermeture;
    }
}