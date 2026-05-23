<?php

namespace App\Service;

// Interface CartInterface : définit le contrat que toute stratégie de panier doit respecter
// Cela permet d'interchanger les implémentations (SessionCart, ApiCart...) sans changer le code appelant
// C'est l'application du principe de substitution de Liskov et du pattern Strategy
interface CartInterface
{
    // Ajoute un produit au panier par son identifiant
    public function add(int $id): void;

    // Supprime entièrement un produit du panier par son identifiant
    public function remove(int $id): void;

    // Diminue la quantité d'un produit de 1 (supprime si quantité = 1)
    public function decrement(int $id): void;

    // Vide complètement le panier
    public function clear(): void;

    // Calcule et retourne le montant total du panier
    public function getTotal(): float;

    // Retourne le tableau complet du panier avec les objets Product et quantités
    public function getFullCart(): array;

    // Retourne le nombre total d'articles (somme de toutes les quantités)
    public function getTotalQuantity(): int;
}
