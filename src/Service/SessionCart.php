<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// SessionCart : implémentation concrète de CartInterface utilisant la session HTTP
// Le panier est stocké dans la session sous la clé 'cart' sous forme de tableau [id => quantité]
class SessionCart implements CartInterface
{
    private $requestStack;       // Permet d'accéder à la session courante
    private $productRepository;  // Permet de récupérer les objets Product depuis la BDD

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    // Méthode privée utilitaire pour accéder à la session depuis n'importe quelle méthode
    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    // Ajoute un produit : incrémente la quantité s'il existe, sinon l'initialise à 1
    public function add(int $id): void
    {
        $cart = $this->getSession()->get('cart', []); // Récupère le panier ou tableau vide
        if (!empty($cart[$id])) {
            $cart[$id]++; // Le produit est déjà dans le panier : on augmente la quantité
        } else {
            $cart[$id] = 1; // Nouveau produit : on l'ajoute avec une quantité de 1
        }
        $this->getSession()->set('cart', $cart); // Sauvegarde le panier mis à jour en session
    }

    // Supprime complètement un produit du panier (peu importe sa quantité)
    public function remove(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]); // Suppression de la clé correspondant à l'ID du produit
        }
        $this->getSession()->set('cart', $cart);
    }

    // Décrémente la quantité de 1 ; si elle atteint 0, le produit est retiré du panier
    public function decrement(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--; // On diminue simplement la quantité
            } else {
                unset($cart[$id]); // Quantité = 1, on supprime le produit du panier
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    // Vide entièrement le panier en supprimant la clé 'cart' de la session
    public function clear(): void
    {
        $this->getSession()->remove('cart');
    }

    // Calcule le total en parcourant le panier complet et en multipliant prix × quantité
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    // Hydrate le panier : transforme les IDs de session en objets Product complets
    public function getFullCart(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $fullCart = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id); // Récupère le produit en BDD
            if ($product) {
                // On construit un tableau associatif avec l'objet Product et la quantité
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        return $fullCart;
    }

    // Calcule la quantité totale d'articles (somme de toutes les quantités du panier)
    public function getTotalQuantity(): int
    {
        $cart = $this->getSession()->get('cart', []);
        $totalQuantity = 0;
        foreach ($cart as $quantity) {
            $totalQuantity += $quantity;
        }
        return $totalQuantity;
    }
}
