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
    // Page d'accueil : récupère tous les produits depuis la base de données et les affiche
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('index.html.twig', [
            'products' => $products
        ]);
    }

    // Page profil : accessible uniquement aux utilisateurs connectés grâce à IsGranted
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('profile.html.twig');
    }

    // Page détails d'un produit : recherche le produit par son ID, retourne 404 s'il n'existe pas
    #[Route('/product/{id}', name: 'app_product_details')]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            // Si le produit n'existe pas en BDD, Symfony génère une page d'erreur 404
            throw $this->createNotFoundException('Product not found');
        }
        return $this->render('product_details.html.twig', [
            'product' => $product
        ]);
    }

    // Page des catégories : récupère toutes les catégories et les passe à la vue
    #[Route('/categories', name: 'app_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('browse_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    // Page panier : utilise CartHandler pour obtenir les articles et le total
    #[Route('/cart', name: 'app_cart')]
    public function cart(CartHandler $cart): Response
    {
        return $this->render('cart.html.twig', [
            'items' => $cart->getFullCart(), // Liste des produits avec leurs quantités
            'total' => $cart->getTotal()     // Montant total calculé
        ]);
    }

    // Page produits par catégorie : filtre les produits selon la catégorie sélectionnée
    #[Route('/category/{id}', name: 'app_products_by_category')]
    public function productsByCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            // Si la catégorie n'existe pas en BDD, on retourne une erreur 404
            throw $this->createNotFoundException('Category not found');
        }
        return $this->render('products_by_category.html.twig', [
            'category' => $category,
            'products' => $category->getProducts() // Relation OneToMany vers les produits
        ]);
    }
}
