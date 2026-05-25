<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// ce controller gère la connexion et la déconnexion
class SecurityController extends AbstractController
{
    // la déconnexion est gérée directement par Symfony via le firewall, cette méthode n'est jamais appelée
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // affiche le formulaire de login et gère les erreurs
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // si l'utilisateur est déjà connecté on le redirige direct vers l'accueil
        if ($this->getUser()) {
             return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError(); // erreur de connexion si y'en a une
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername(); // pour pré-remplir le champ email

        // on affiche aussi le form d'inscription sur la même page
        $form = $this->createForm(RegistrationFormType::class, new User());

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'registrationForm' => $form->createView()
        ]);
    }
}
