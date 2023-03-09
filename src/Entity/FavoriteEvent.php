<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FavoriteEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteEventRepository::class)]
#[ApiResource]
class FavoriteEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoriteEvents')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'favoriteEvents')]
    private ?Evenement $event = null;

 
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvents(): ?Evenement
    {
        return $this->event;
    }

    public function setEvents(?Evenement $events): self
    {
        $this->event = $events;

        return $this;
    }

   
}
