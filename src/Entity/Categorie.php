<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['categoriereclamation'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['categoriereclamation'])]
    private ?string $NomCategorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->NomCategorie;
    }

    public function setNomCategorie(?string $NomCategorie): self
    {
        $this->NomCategorie = $NomCategorie;

        return $this;
    }
}
