<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// implémentation du panier qui utilise la session HTTP pour stocker les articles
// le panier est stocké sous forme de tableau [id_produit => quantité] dans la session
class SessionCart implements CartInterface
{
    private $requestStack;       // pour accéder à la session
    private $productRepository;  // pour récupérer les produits depuis la BDD

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    // méthode utilitaire pour récupérer la session sans se répéter
    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    // si le produit est déjà dans le panier on incrémente, sinon on l'ajoute à 1
    public function add(int $id): void
    {
        $cart = $this->getSession()->get('cart', []); // on récupère le panier ou un tableau vide
        if (!empty($cart[$id])) {
            $cart[$id]++; // produit déjà là, on augmente la quantité
        } else {
            $cart[$id] = 1; // nouveau produit, quantité à 1
        }
        $this->getSession()->set('cart', $cart); // on sauvegarde en session
    }

    // supprime le produit du panier peu importe la quantité
    public function remove(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]); // on enlève la clé du tableau
        }
        $this->getSession()->set('cart', $cart);
    }

    // réduit la quantité de 1, si on est déjà à 1 on supprime le produit
    public function decrement(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--; // on baisse juste la quantité
            } else {
                unset($cart[$id]); // quantité = 1, on retire le produit
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    // vide tout le panier en supprimant la clé de session
    public function clear(): void
    {
        $this->getSession()->remove('cart');
    }

    // calcule le prix total du panier
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    // retourne le panier complet avec les objets Product et les quantités
    public function getFullCart(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $fullCart = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id); // on va chercher le produit en BDD
            if ($product) {
                // on construit un tableau avec le produit et sa quantité
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        return $fullCart;
    }

    // retourne le nombre total d'articles dans le panier
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
