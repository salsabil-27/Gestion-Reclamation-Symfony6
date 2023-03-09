<?php

namespace App\Entity;

use App\Repository\RegisterEvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegisterEvenementRepository::class)]
class RegisterEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\ManyToOne(inversedBy: 'registerEvenements')]
    private ?evenement $evenement = null;

    #[ORM\ManyToOne(inversedBy: 'registerEvenements')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $nameRegister = null;

    #[ORM\Column(length: 255)]
    private ?string $EmailRegister = null;

  
 

 

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getEvenement(): ?evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNameRegister(): ?string
    {
        return $this->nameRegister;
    }

    public function setNameRegister(string $nameRegister): self
    {
        $this->nameRegister = $nameRegister;

        return $this;
    }

    public function getEmailRegister(): ?string
    {
        return $this->EmailRegister;
    }

    public function setEmailRegister(string $EmailRegister): self
    {
        $this->EmailRegister = $EmailRegister;

        return $this;
    }

 

   

    
  
}
