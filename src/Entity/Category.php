<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Entité Category : représente une catégorie de produits en base de données
#[ORM\Entity]
class Category
{
    // Identifiant unique auto-généré par Doctrine
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nom de la catégorie, limité à 255 caractères
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Relation OneToMany : une catégorie contient plusieurs produits
    // mappedBy indique que c'est la propriété 'category' dans Product qui détient la clé étrangère
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    // Le constructeur initialise la collection de produits comme une ArrayCollection vide
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    // Retourne l'identifiant de la catégorie
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne le nom de la catégorie
    public function getName(): ?string
    {
        return $this->name;
    }

    // Définit le nom de la catégorie
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    // Retourne la collection de tous les produits liés à cette catégorie
    public function getProducts(): Collection
    {
        return $this->products;
    }

    // Ajoute un produit à la catégorie si ce produit n'y est pas déjà
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            // On met aussi à jour le côté owning (Product) pour maintenir la cohérence
            $product->setCategory($this);
        }

        return $this;
    }

    // Retire un produit de la catégorie et met la clé étrangère à null côté Product
    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
