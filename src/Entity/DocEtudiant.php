<?php

namespace App\Entity;

use App\Repository\DocEtudiantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocEtudiantRepository::class)]
class DocEtudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idDocet = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomAssSco = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nAssSco = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $adresseAssSco = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastVacc = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomDoc = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $adresseDoc = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telDoc = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $nSecuSocial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $carteVitale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $diplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoIdentite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certificatScolarite = null;

    // Getters et Setters
    public function getIdDocet(): ?int
    {
        return $this->idDocet;
    }

    public function getNomAssSco(): ?string
    {
        return $this->nomAssSco;
    }

    public function setNomAssSco(?string $nomAssSco): static
    {
        $this->nomAssSco = $nomAssSco;
        return $this;
    }

    public function getNAssSco(): ?string
    {
        return $this->nAssSco;
    }

    public function setNAssSco(?string $nAssSco): static
    {
        $this->nAssSco = $nAssSco;
        return $this;
    }

    public function getAdresseAssSco(): ?string
    {
        return $this->adresseAssSco;
    }

    public function setAdresseAssSco(?string $adresseAssSco): static
    {
        $this->adresseAssSco = $adresseAssSco;
        return $this;
    }

    public function getLastVacc(): ?\DateTimeInterface
    {
        return $this->lastVacc;
    }

    public function setLastVacc(?\DateTimeInterface $lastVacc): static
    {
        $this->lastVacc = $lastVacc;
        return $this;
    }

    public function getNomDoc(): ?string
    {
        return $this->nomDoc;
    }

    public function setNomDoc(?string $nomDoc): static
    {
        $this->nomDoc = $nomDoc;
        return $this;
    }

    public function getAdresseDoc(): ?string
    {
        return $this->adresseDoc;
    }

    public function setAdresseDoc(?string $adresseDoc): static
    {
        $this->adresseDoc = $adresseDoc;
        return $this;
    }

    public function getTelDoc(): ?string
    {
        return $this->telDoc;
    }

    public function setTelDoc(?string $telDoc): static
    {
        $this->telDoc = $telDoc;
        return $this;
    }

    public function getNSecuSocial(): ?string
    {
        return $this->nSecuSocial;
    }

    public function setNSecuSocial(?string $nSecuSocial): static
    {
        $this->nSecuSocial = $nSecuSocial;
        return $this;
    }

    public function getCarteVitale(): ?string
    {
        return $this->carteVitale;
    }

    public function setCarteVitale(?string $carteVitale): static
    {
        $this->carteVitale = $carteVitale;
        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): static
    {
        $this->diplome = $diplome;
        return $this;
    }

    public function getPhotoIdentite(): ?string
    {
        return $this->photoIdentite;
    }

    public function setPhotoIdentite(?string $photoIdentite): static
    {
        $this->photoIdentite = $photoIdentite;
        return $this;
    }

    public function getCertificatScolarite(): ?string
    {
        return $this->certificatScolarite;
    }

    public function setCertificatScolarite(?string $certificatScolarite): static
    {
        $this->certificatScolarite = $certificatScolarite;
        return $this;
    }
}