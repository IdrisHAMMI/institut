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
    /**
     * @Assert\NotBlank
     * @Assert\Range(
     *     min = 100,
     *     max = 1000,
     *     notInRangeMessage = "Matricule must be between {{ min }} and {{ max }}",
     * )
     */
    private ?string $matricule = null;

    #[ORM\Column(length: 30)]
    /**
     * @Assert\NotBlank(message="Nom cannot be blank.")
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "Nom must be at least {{ limit }} characters long",
     *     maxMessage = "Nom cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    /**
     * @Assert\NotBlank(message="Nom cannot be blank.")
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "Nom must be at least {{ limit }} characters long",
     *     maxMessage = "Nom cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $prenom = null;

    /**
     * @var Collection<int, Matiere>
     */
    #[ORM\OneToMany(targetEntity: Matiere::class, mappedBy: 'professeur')]
    private Collection $matieres;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
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
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->setProfesseur($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getProfesseur() === $this) {
                $matiere->setProfesseur(null);
            }
        }

        return $this;
    }
}
