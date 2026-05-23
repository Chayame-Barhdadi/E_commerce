<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entité Product : représente un produit en base de données via Doctrine ORM
#[ORM\Entity]
class Product
{
    // Clé primaire auto-incrémentée générée automatiquement par Doctrine
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nom du produit, limité à 255 caractères
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Description longue du produit, stockée en TEXT (nullable car optionnelle)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    // Prix du produit stocké en float
    #[ORM\Column]
    private ?float $price = null;

    // Chemin ou nom du fichier image (nullable : un produit peut ne pas avoir d'image)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    // Relation ManyToOne : plusieurs produits appartiennent à une seule catégorie
    // inversedBy indique la propriété côté Category qui référence cette relation
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private ?Category $category = null;

    // Retourne l'identifiant unique du produit
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne le nom du produit
    public function getName(): ?string
    {
        return $this->name;
    }

    // Définit le nom du produit, retourne $this pour permettre le chaînage
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // Retourne la description du produit
    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Définit la description (nullable : peut être null)
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    // Retourne le prix du produit
    public function getPrice(): ?float
    {
        return $this->price;
    }

    // Définit le prix du produit
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    // Retourne le nom du fichier image
    public function getImage(): ?string
    {
        return $this->image;
    }

    // Définit l'image du produit (nullable)
    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    // Retourne la catégorie associée au produit
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    // Associe une catégorie au produit (nullable : un produit peut être sans catégorie)
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}
