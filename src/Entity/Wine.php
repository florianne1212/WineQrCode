<?php

namespace App\Entity;

use App\Repository\WineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WineRepository::class)]
class Wine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column]
    private ?float $alcoholContent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: UserFavoriteWine::class, mappedBy: 'wine')]
    private $userFavoriteWine;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $grapes = null;

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $ingredients = null;

    #[ORM\ManyToOne(inversedBy: 'Wine')]
    private ?Winery $winery = null;

    #[ORM\ManyToOne(inversedBy: 'wines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;


    public function __construct()
    {
        $this->userFavoriteWines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getAlcoholContent(): ?float
    {
        return $this->alcoholContent;
    }

    public function setAlcoholContent(float $alcoholContent): static
    {
        $this->alcoholContent = $alcoholContent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGrapes(): ?array
    {
        return $this->grapes;
    }

    public function setGrapes(?array $grapes): static
    {
        $this->grapes = $grapes;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->ingredients;
    }

    public function setIngredients(?array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getWinery(): ?Winery
    {
        return $this->winery;
    }

    public function setWinery(?Winery $winery): static
    {
        $this->winery = $winery;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

}
