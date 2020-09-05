<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteRepository::class)
 */
class Poste
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=LettreMotiv::class, mappedBy="NomPoste")
     */
    private $lettreMotivs;

    public function __construct()
    {
        $this->lettreMotivs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|LettreMotiv[]
     */
    public function getLettreMotivs(): Collection
    {
        return $this->lettreMotivs;
    }

    public function addLettreMotiv(LettreMotiv $lettreMotiv): self
    {
        if (!$this->lettreMotivs->contains($lettreMotiv)) {
            $this->lettreMotivs[] = $lettreMotiv;
            $lettreMotiv->setNomPoste($this);
        }

        return $this;
    }

    public function removeLettreMotiv(LettreMotiv $lettreMotiv): self
    {
        if ($this->lettreMotivs->contains($lettreMotiv)) {
            $this->lettreMotivs->removeElement($lettreMotiv);
            // set the owning side to null (unless already changed)
            if ($lettreMotiv->getNomPoste() === $this) {
                $lettreMotiv->setNomPoste(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return (string) $this->getNom();
    }
}
