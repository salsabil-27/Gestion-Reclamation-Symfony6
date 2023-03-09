<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstNameUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailUser = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $NumTelephoneUser = null;

    #[ORM\Column(nullable: true)]
    private ?int $cinUser = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FavoriteEvent::class)]
    private Collection $favoriteEvents;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?RegisterEvenement $registerEvenement = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RegisterEvenement::class)]
    private Collection $registerEvenements;

    public function __construct()
    {
        $this->favoriteEvents = new ArrayCollection();
        $this->registerEvenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstNameUser(): ?string
    {
        return $this->firstNameUser;
    }

    public function setFirstNameUser(?string $firstNameUser): self
    {
        $this->firstNameUser = $firstNameUser;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(?string $emailUser): self
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNumTelephoneUser(): ?int
    {
        return $this->NumTelephoneUser;
    }

    public function setNumTelephoneUser(int $NumTelephoneUser): self
    {
        $this->NumTelephoneUser = $NumTelephoneUser;

        return $this;
    }

    public function getCinUser(): ?int
    {
        return $this->cinUser;
    }

    public function setCinUser(?int $cinUser): self
    {
        $this->cinUser = $cinUser;

        return $this;
    }

    /**
     * @return Collection<int, FavoriteEvent>
     */
    public function getFavoriteEvents(): Collection
    {
        return $this->favoriteEvents;
    }

    public function addFavoriteEvent(FavoriteEvent $favoriteEvent): self
    {
        if (!$this->favoriteEvents->contains($favoriteEvent)) {
            $this->favoriteEvents->add($favoriteEvent);
            $favoriteEvent->setUser($this);
        }

        return $this;
    }

    public function removeFavoriteEvent(FavoriteEvent $favoriteEvent): self
    {
        if ($this->favoriteEvents->removeElement($favoriteEvent)) {
            // set the owning side to null (unless already changed)
            if ($favoriteEvent->getUser() === $this) {
                $favoriteEvent->setUser(null);
            }
        }

        return $this;
    }

    public function getRegisterEvenement(): ?RegisterEvenement
    {
        return $this->registerEvenement;
    }

    public function setRegisterEvenement(?RegisterEvenement $registerEvenement): self
    {
        $this->registerEvenement = $registerEvenement;

        return $this;
    }

    /**
     * @return Collection<int, RegisterEvenement>
     */
    public function getRegisterEvenements(): Collection
    {
        return $this->registerEvenements;
    }

    public function addRegisterEvenement(RegisterEvenement $registerEvenement): self
    {
        if (!$this->registerEvenements->contains($registerEvenement)) {
            $this->registerEvenements->add($registerEvenement);
            $registerEvenement->setUser($this);
        }

        return $this;
    }

    public function removeRegisterEvenement(RegisterEvenement $registerEvenement): self
    {
        if ($this->registerEvenements->removeElement($registerEvenement)) {
            // set the owning side to null (unless already changed)
            if ($registerEvenement->getUser() === $this) {
                $registerEvenement->setUser(null);
            }
        }

        return $this;
    }
}
