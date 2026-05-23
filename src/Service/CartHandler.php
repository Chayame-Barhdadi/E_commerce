<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

// CartHandler : classe façade qui applique le pattern Strategy pour la gestion du panier
// Elle délègue toutes les opérations à une implémentation concrète de CartInterface
// Cela permet de changer de stratégie (session, API...) sans modifier les contrôleurs
class CartHandler
{
    private $cartStrategy; // Stocke la stratégie active (SessionCart ou ApiCart)

    public function __construct(
        // L'attribut Autowire force l'injection de SessionCart comme stratégie par défaut
        // On peut remplacer SessionCart::class par ApiCart::class pour changer de comportement
        #[Autowire(service: SessionCart::class)]
        CartInterface $cartStrategy
    )
    {
        $this->cartStrategy = $cartStrategy;
    }

    // Délègue l'ajout d'un produit à la stratégie active
    public function add(int $id): void
    {
        $this->cartStrategy->add($id);
    }

    // Délègue la suppression d'un produit à la stratégie active
    public function remove(int $id): void
    {
        $this->cartStrategy->remove($id);
    }

    // Délègue la décrémentation à la stratégie active
    public function decrement(int $id): void
    {
        $this->cartStrategy->decrement($id);
    }

    // Délègue le vidage du panier à la stratégie active
    public function clear(): void
    {
        $this->cartStrategy->clear();
    }

    // Délègue le calcul du total à la stratégie active
    public function getTotal(): float
    {
        return $this->cartStrategy->getTotal();
    }

    // Délègue la récupération complète du panier à la stratégie active
    public function getFullCart(): array
    {
        return $this->cartStrategy->getFullCart();
    }

    // Délègue le calcul de la quantité totale à la stratégie active
    public function getTotalQuantity(): int
    {
        return $this->cartStrategy->getTotalQuantity();
    }
}
