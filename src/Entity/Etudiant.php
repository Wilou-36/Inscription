<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idEtudiant = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $sexe = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $adresseMedicale = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $diplome = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $draftJson = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $statut = 'en_attente'; // en_attente, valide, refuse

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $messageAdmin = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_adresse', referencedColumnName: 'id_adresse', nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id_transport', nullable: false)]
    private ?Transport $transport = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_docet', referencedColumnName: 'id_docet', nullable: false)]
    private ?DocEtudiant $docEtudiant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_sco', referencedColumnName: 'id_sco', nullable: false)]
    private ?DossSco $dossSco = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_respleg', referencedColumnName: 'id_respleg', nullable: false)]
    private ?ResponsableLegal $responsableLegal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id', nullable: false)]
    private ?Utilisateur $utilisateur = null;

    // Getters et Setters
    public function getIdEtudiant(): ?int
    {
        return $this->idEtudiant;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function isSexe(): ?bool
    {
        return $this->sexe;
    }

    public function setSexe(?bool $sexe): static
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getAdresseMedicale(): ?string
    {
        return $this->adresseMedicale;
    }

    public function setAdresseMedicale(?string $adresseMedicale): static
    {
        $this->adresseMedicale = $adresseMedicale;
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

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(?Transport $transport): static
    {
        $this->transport = $transport;
        return $this;
    }

    public function getDocEtudiant(): ?DocEtudiant
    {
        return $this->docEtudiant;
    }

    public function setDocEtudiant(?DocEtudiant $docEtudiant): static
    {
        $this->docEtudiant = $docEtudiant;
        return $this;
    }

    public function getDossSco(): ?DossSco
    {
        return $this->dossSco;
    }

    public function setDossSco(?DossSco $dossSco): static
    {
        $this->dossSco = $dossSco;
        return $this;
    }

    public function getResponsableLegal(): ?ResponsableLegal
    {
        return $this->responsableLegal;
    }

    public function setResponsableLegal(?ResponsableLegal $responsableLegal): static
    {
        $this->responsableLegal = $responsableLegal;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }


    public function getDraftJson(): ?string
    {
        return $this->draftJson;
    }

    public function setDraftJson(?string $draftJson): static
    {
        $this->draftJson = $draftJson;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getMessageAdmin(): ?string
    {
        return $this->messageAdmin;
    }

    public function setMessageAdmin(?string $messageAdmin): static
    {
        $this->messageAdmin = $messageAdmin;
        return $this;
    }
}

