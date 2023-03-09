<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;



#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ApiResource]

class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    


    #[Assert\NotBlank(message:"titreEvent is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titreEvent = null;
    #[Assert\NotBlank(message:"DateDebutEvent  is required")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateDebutEvent = null;
    #[Assert\NotBlank(message:"dateFinEvent  is required")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFinEvent = null;
    #[Assert\NotBlank(message:"placeEvent  is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $placeEvent = null;
    #[Assert\NotBlank(message:"DescriptionEvent is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DescriptionEvent = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?TypeEvenement $type = null;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $imageEvenement = null;

    #[ORM\OneToMany(mappedBy: 'events', targetEntity: FavoriteEvent::class)]
    private Collection $favoriteEvents;

    #[ORM\ManyToOne(inversedBy: 'evenement')]
    private ?RegisterEvenement $registerEvenement = null;

    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: RegisterEvenement::class)]
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

    public function getTitreEvent(): ?string
    {
        return $this->titreEvent;
    }

    public function setTitreEvent(?string $titreEvent): self
    {
        $this->titreEvent = $titreEvent;

        return $this;
    }

    public function getDateDebutEvent(): ?\DateTimeInterface
    {
        return $this->DateDebutEvent;
    }

    public function setDateDebutEvent(?\DateTimeInterface $DateDebutEvent): self
    {
        $this->DateDebutEvent = $DateDebutEvent;

        return $this;
    }

    public function getDateFinEvent(): ?\DateTimeInterface
    {
        return $this->dateFinEvent;
    }

    public function setDateFinEvent(?\DateTimeInterface $dateFinEvent): self
    {
        $this->dateFinEvent = $dateFinEvent;

        return $this;
    }

    public function getPlaceEvent(): ?string
    {
        return $this->placeEvent;
    }

    public function setPlaceEvent(?string $placeEvent): self
    {
        $this->placeEvent = $placeEvent;

        return $this;
    }

    public function getDescriptionEvent(): ?string
    {
        return $this->DescriptionEvent;
    }

    public function setDescriptionEvent(?string $DescriptionEvent): self
    {
        $this->DescriptionEvent = $DescriptionEvent;

        return $this;
    }

    public function getType(): ?TypeEvenement
    {
        return $this->type;
    }

    public function setType(?TypeEvenement $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImageEvenement(): ?string
    {
        return $this->imageEvenement;
    }

    public function setImageEvenement(?string $imageEvenement): self
    {
        $this->imageEvenement = $imageEvenement;

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
            $favoriteEvent->setEvents($this);
        }

        return $this;
    }

    public function removeFavoriteEvent(FavoriteEvent $favoriteEvent): self
    {
        if ($this->favoriteEvents->removeElement($favoriteEvent)) {
            // set the owning side to null (unless already changed)
            if ($favoriteEvent->getEvents() === $this) {
                $favoriteEvent->setEvents(null);
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
            $registerEvenement->setEvenement($this);
        }

        return $this;
    }

    public function removeRegisterEvenement(RegisterEvenement $registerEvenement): self
    {
        if ($this->registerEvenements->removeElement($registerEvenement)) {
            // set the owning side to null (unless already changed)
            if ($registerEvenement->getEvenement() === $this) {
                $registerEvenement->setEvenement(null);
            }
        }

        return $this;
    }

  
  


 
}
