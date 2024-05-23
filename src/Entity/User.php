<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: UserFavoriteWine::class, mappedBy: 'user')]
    private  $winesFavoritedByUser;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\OneToOne(mappedBy: 'User', cascade: ['persist', 'remove'])]
    private ?Winery $winery = null;

    /**
     * @var Collection<int, Wine>
     */
    #[ORM\OneToMany(targetEntity: Wine::class, mappedBy: 'owner')]
    private Collection $wines;


    public function __construct()
    {
        $this->userFavoriteWines = new ArrayCollection();
        $this->wines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getWinesFavoritedByUser(): Collection
    {
        return $this->winesFavoritedByUser;
    }

    public function isWineFavoritedByUser(Wine $wine): bool
    {
        foreach ($this->winesFavoritedByUser as $userFavoriteWine) {
            if ($userFavoriteWine->getWine() === $wine) {
                return true;
            }
        }
        return false;
    }

    public function removeWineFavorite(Wine $wine): Collection
    {
        foreach ($this->winesFavoritedByUser as $userFavoriteWine) {
            if ($userFavoriteWine->getWine() === $wine) {
                return $this->winesFavoritedByUser;
                // return $this->winesFavoritedByUser->removeElement($userFavoriteWine);
                // return true;
            }
        }
        // return false;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getWinery(): ?Winery
    {
        return $this->winery;
    }

    public function setWinery(Winery $winery): static
    {
        // set the owning side of the relation if necessary
        if ($winery->getUser() !== $this) {
            $winery->setUser($this);
        }

        $this->winery = $winery;

        return $this;
    }

    /**
     * @return Collection<int, Wine>
     */
    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function addWine(Wine $wine): static
    {
        if (!$this->wines->contains($wine)) {
            $this->wines->add($wine);
            $wine->setOwner($this);
        }

        return $this;
    }

    public function removeWine(Wine $wine): static
    {
        if ($this->wines->removeElement($wine)) {
            // set the owning side to null (unless already changed)
            if ($wine->getOwner() === $this) {
                $wine->setOwner(null);
            }
        }

        return $this;
    }
}
