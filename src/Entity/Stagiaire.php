<?php

namespace App\Entity;

use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StagiaireRepository::class)]
class Stagiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
     * @Assert\NotBlank(message="Adresse cannot be blank.")
     */
    private ?string $adresse = null;

    #[ORM\Column]

    private ?int $code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    /**
     * @Assert\NotBlank(message="Date inscription cannot be blank.")
     * @Assert\Date(message="Invalid date format.")
     */
    private ?\DateTimeInterface $dateInscription = null;

    /**
     * @var Collection<int, Stage>
     */
    #[ORM\ManyToMany(targetEntity: Stage::class, inversedBy: 'stagiaires')]
    private Collection $Stage;

    public function __construct()
    {
        $this->Stage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getStage(): Collection
    {
        return $this->Stage;
    }

    public function addStage(Stage $stage): static
    {
        if (!$this->Stage->contains($stage)) {
            $this->Stage->add($stage);
        }

        return $this;
    }

    public function removeStage(Stage $stage): static
    {
        $this->Stage->removeElement($stage);

        return $this;
    }
}
