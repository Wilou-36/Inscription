<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idTransport = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $vehicule = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $pDimmatriculation = null;

    public function getIdTransport(): ?string
    {
        return $this->idTransport;
    }

    public function setIdTransport(string $idTransport): static
    {
        $this->idTransport = $idTransport;
        return $this;
    }

    public function getVehicule(): ?string
    {
        return $this->vehicule;
    }

    public function setVehicule(?string $vehicule): static
    {
        $this->vehicule = $vehicule;
        return $this;
    }

    public function getPDimmatriculation(): ?string
    {
        return $this->pDimmatriculation;
    }

    public function setPDimmatriculation(?string $pDimmatriculation): static
    {
        $this->pDimmatriculation = $pDimmatriculation;
        return $this;
    }
}