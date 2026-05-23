<?php

namespace App\Controller;

use App\Service\CartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Contrôleur responsable des actions sur le panier (ajout, suppression, décrémentation, vidage)
class CartController extends AbstractController
{
    // Ajoute un produit au panier via son ID, puis redirige vers la page panier
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id, CartHandler $cart): Response
    {
        $cart->add($id); // Délègue l'ajout au service CartHandler
        $this->addFlash('success', 'Produit ajouté au panier !'); // Message flash affiché une seule fois
        return $this->redirectToRoute('app_cart');
    }

    // Supprime complètement un produit du panier (quelle que soit sa quantité)
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(int $id, CartHandler $cart): Response
    {
        $cart->remove($id); // Suppression totale du produit dans la session
        $this->addFlash('info', 'Produit retiré du panier.');
        return $this->redirectToRoute('app_cart');
    }

    // Décrémente la quantité d'un produit de 1 ; si quantité = 1, le produit est supprimé
    #[Route('/cart/decrement/{id}', name: 'cart_decrement')]
    public function decrement(int $id, CartHandler $cart): Response
    {
        $cart->decrement($id);
        return $this->redirectToRoute('app_cart');
    }

    // Vide complètement le panier (supprime la clé 'cart' de la session)
    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(CartHandler $cart): Response
    {
        $cart->clear();
        return $this->redirectToRoute('app_cart');
    }
}
