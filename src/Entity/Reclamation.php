<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message:"email is not a valid email")]
    #[Assert\NotBlank(message:"email IS required")]
    private ?string $emailReclamation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"objetR IS required")]
    private ?string $ObjetReclamation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"contenue IS required")]
    private ?string $ContenueReclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?CategorieReclamation $type = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailReclamation(): ?string
    {
        return $this->emailReclamation;
    }

    public function setEmailReclamation(?string $emailReclamation): self
    {
        $this->emailReclamation = $emailReclamation;

        return $this;
    }

    public function getObjetReclamation(): ?string
    {
        return $this->ObjetReclamation;
    }

    public function setObjetReclamation(?string $ObjetReclamation): self
    {
        $this->ObjetReclamation = $ObjetReclamation;

        return $this;
    }

    public function getContenueReclamation(): ?string
    {
        return $this->ContenueReclamation;
    }

    public function setContenueReclamation(?string $ContenueReclamation): self
    {
        $this->ContenueReclamation = $ContenueReclamation;

        return $this;
    }

    public function getType(): ?CategorieReclamation
    {
        return $this->type;
    }

    public function setType(?CategorieReclamation $type): self
    {
        $this->type = $type;

        return $this;
    }

 
}
