<?php

namespace App\Controller;

use App\Service\CartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// gère tout ce qui touche au panier (ajouter, enlever, vider...)
class CartController extends AbstractController
{
    // vide tout le panier d'un coup
    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(CartHandler $cart): Response
    {
        $cart->clear();
        return $this->redirectToRoute('app_cart');
    }

    // réduit la quantité de 1, si c'est déjà 1 ça supprime le produit
    #[Route('/cart/decrement/{id}', name: 'cart_decrement')]
    public function decrement(int $id, CartHandler $cart): Response
    {
        $cart->decrement($id);
        return $this->redirectToRoute('app_cart');
    }

    // ajoute un produit au panier et redirige vers le panier
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id, CartHandler $cart): Response
    {
        $cart->add($id);
        $this->addFlash('success', 'Article ajouté au panier avec succès !');
        return $this->redirectToRoute('app_cart');
    }

    // supprime un produit du panier peu importe la quantité
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(int $id, CartHandler $cart): Response
    {
        $cart->remove($id);
        $this->addFlash('info', 'Produit retiré du panier.');
        return $this->redirectToRoute('app_cart');
    }
}
