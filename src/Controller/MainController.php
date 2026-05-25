<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\CartHandler;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    // page panier, on passe les articles et le total au template
    #[Route('/cart', name: 'app_cart')]
    public function cart(CartHandler $cart): Response
    {
        return $this->render('cart.html.twig', [
            'items' => $cart->getFullCart(),
            'total' => $cart->getTotal()
        ]);
    }

    // affiche les produits d'une catégorie donnée, 404 si la catégorie n'existe pas
    #[Route('/category/{id}', name: 'app_products_by_category')]
    public function productsByCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        return $this->render('products_by_category.html.twig', [
            'category' => $category,
            'products' => $category->getProducts()
        ]);
    }

    // on récupère toutes les catégories pour les afficher
    #[Route('/categories', name: 'app_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('browse_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    // page d'accueil, on récupère tous les produits et on les envoie à la vue
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('index.html.twig', [
            'products' => $products
        ]);
    }

    // on cherche le produit par son id, si il existe pas on renvoie une 404
    #[Route('/product/{id}', name: 'app_product_details')]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable');
        }
        return $this->render('product_details.html.twig', [
            'product' => $product
        ]);
    }

    // la page profil est protégée, faut être connecté pour y accéder
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('profile.html.twig');
    }
}
