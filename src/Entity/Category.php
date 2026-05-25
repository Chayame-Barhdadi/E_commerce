<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// entité qui représente une catégorie de produits
#[ORM\Entity]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // id généré automatiquement

    #[ORM\Column(length: 255)]
    private ?string $name = null; // nom de la catégorie

    // une catégorie peut avoir plusieurs produits (relation OneToMany)
    // mappedBy correspond au champ 'category' dans l'entité Product
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    // on initialise la collection dans le constructeur pour éviter les erreurs
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    // retourne tous les produits de cette catégorie
    public function getProducts(): Collection
    {
        return $this->products;
    }

    // ajoute un produit à la catégorie si il n'y est pas déjà
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            // on met aussi à jour le côté Product pour rester cohérent
            $product->setCategory($this);
        }

        return $this;
    }

    // retire un produit de la catégorie
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
