<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// controller pour l'inscription d'un nouvel utilisateur
class RegistrationController extends AbstractController
{
    // accepte GET pour afficher le formulaire et POST quand l'utilisateur soumet
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserService $userService): Response
    {
        $user = new User(); // nouvel utilisateur vide
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request); // on remplit le formulaire avec les données POST

        if ($form->isSubmitted() && $form->isValid()) {
            // on délègue l'enregistrement au service UserService
            $userService->registerUser(
                $user,
                $form->get('plainPassword')->getData() // mot de passe en clair récupéré du form
            );

            $this->addFlash('success', 'Votre compte a été créé avec succès !');
            return $this->redirectToRoute('app_login'); // on redirige vers la page de connexion
        }

        return $this->render('login.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
