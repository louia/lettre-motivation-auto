<?php

namespace App\Entity;

use App\Repository\LettreMotivRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LettreMotivRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class LettreMotiv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wordFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NomEntreprise;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class, inversedBy="lettreMotivs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $NomPoste;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villeCodeP;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordFilename(): ?string
    {
        return $this->wordFilename;
    }

    public function setWordFilename(string $wordFilename): self
    {
        $this->wordFilename = $wordFilename;

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->NomEntreprise;
    }

    public function setNomEntreprise(string $NomEntreprise): self
    {
        $this->NomEntreprise = $NomEntreprise;

        return $this;
    }

    public function getNomPoste(): ?Poste
    {
        return $this->NomPoste;
    }

    public function setNomPoste(?Poste $NomPoste): self
    {
        $this->NomPoste = $NomPoste;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        $this->CreatedAt = new \DateTime();
        return $this;
    }

    public function getVilleCodeP(): ?string
    {
        return $this->villeCodeP;
    }

    public function setVilleCodeP(?string $villeCodeP): self
    {
        $this->villeCodeP = $villeCodeP;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

}
