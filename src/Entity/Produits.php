<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"produits:read", "produits:detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"produits:read", "produits:detail"})
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     * @Groups({"produits:read", "produits:detail"})
     * @Assert\NotBlank()
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("produits:detail")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("produits:detail")
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("produits:detail")
     */
    private $memory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMemory(): ?string
    {
        return $this->memory;
    }

    public function setMemory(?string $memory): self
    {
        $this->memory = $memory;

        return $this;
    }
}
