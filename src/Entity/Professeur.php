<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfesseurRepository::class)]
class Professeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $matricule = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    /**
     * @var Collection<int, Matiere>
     */
    #[ORM\ManyToOne(targetEntity: Matiere::class, mappedBy: 'professeur')]
    private Collection $Matiere;

    public function __construct()
    {
        $this->Matiere = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
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

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatiere(): Collection
    {
        return $this->Matiere;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->Matiere->contains($matiere)) {
            $this->Matiere->add($matiere);
            $matiere->setProfesseur($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->Matiere->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getProfesseur() === $this) {
                $matiere->setProfesseur(null);
            }
        }

        return $this;
    }
}
