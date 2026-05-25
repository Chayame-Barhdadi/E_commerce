<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

// CartHandler c'est la classe qui fait le lien entre le controller et la vraie logique du panier
// elle utilise le pattern Strategy donc on peut changer l'implémentation facilement
class CartHandler
{
    private $cartStrategy; // la stratégie qu'on utilise (session ou api)

    public function __construct(
        // par défaut on utilise SessionCart, on peut changer ça ici si besoin
        #[Autowire(service: SessionCart::class)]
        CartInterface $cartStrategy
    )
    {
        $this->cartStrategy = $cartStrategy;
    }

    // ajoute un produit
    public function add(int $id): void
    {
        $this->cartStrategy->add($id);
    }

    // enlève un produit
    public function remove(int $id): void
    {
        $this->cartStrategy->remove($id);
    }

    // diminue la quantité de 1
    public function decrement(int $id): void
    {
        $this->cartStrategy->decrement($id);
    }

    // vide le panier
    public function clear(): void
    {
        $this->cartStrategy->clear();
    }

    // retourne le prix total
    public function getTotal(): float
    {
        return $this->cartStrategy->getTotal();
    }

    // retourne tous les articles du panier avec leurs infos
    public function getFullCart(): array
    {
        return $this->cartStrategy->getFullCart();
    }

    // retourne le nombre total d'articles
    public function getTotalQuantity(): int
    {
        return $this->cartStrategy->getTotalQuantity();
    }
}
