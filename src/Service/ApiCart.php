<?php

namespace App\Service;

// ApiCart : implémentation alternative de CartInterface simulant un panier distant via API
// Cette classe démontre le pattern Strategy : on peut l'injecter à la place de SessionCart
// dans CartHandler sans changer une seule ligne de code côté contrôleur
class ApiCart implements CartInterface
{
    // Simule un appel API pour ajouter un produit (corps vide intentionnel)
    public function add(int $id): void
    {
        // Simulate API call to add product
    }

    // Simule un appel API pour supprimer un produit
    public function remove(int $id): void
    {
        // Simulate API call to remove product
    }

    // Simule un appel API pour décrémenter la quantité d'un produit
    public function decrement(int $id): void
    {
        // Simulate API call to decrement quantity
    }

    // Simule un appel API pour vider le panier
    public function clear(): void
    {
        // Simulate API call to clear cart
    }

    // Retourne 0.0 en attendant une vraie implémentation avec appel API
    public function getTotal(): float
    {
        return 0.0; // Simulate fetching total from API
    }

    // Retourne un tableau vide en attendant une vraie implémentation avec appel API
    public function getFullCart(): array
    {
        return []; // Simulate fetching full cart from API
    }

    // Retourne 0 en attendant une vraie implémentation avec appel API
    public function getTotalQuantity(): int
    {
        return 0; // Simulate fetching total quantity from API
    }
}
