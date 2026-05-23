<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Contrôleur gérant l'inscription d'un nouvel utilisateur
class RegistrationController extends AbstractController
{
    // Accepte les méthodes GET (affichage du formulaire) et POST (soumission du formulaire)
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserService $userService): Response
    {
        // Création d'un nouvel objet User vide qui sera rempli par le formulaire
        $user = new User();
        // Création du formulaire lié à l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Hydrate le formulaire avec les données de la requête HTTP (POST)
        $form->handleRequest($request);

        // Vérifie que le formulaire a bien été soumis et que les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Délègue l'enregistrement au UserService (hashage du mot de passe + persistance)
            $userService->registerUser(
                $user,
                $form->get('plainPassword')->getData() // Mot de passe en clair récupéré du formulaire
            );

            $this->addFlash('success', 'Votre compte a été créé avec succès !');
            // Redirection vers la page de connexion après inscription réussie
            return $this->redirectToRoute('app_login');
        }

        return $this->render('login.html.twig', [
            'registrationForm' => $form->createView(), // Passe la vue du formulaire au template Twig
        ]);
    }
}
