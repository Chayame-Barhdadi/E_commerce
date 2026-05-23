<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// Contrôleur gérant l'authentification : connexion et déconnexion
class SecurityController extends AbstractController
{
    // Route de connexion : affiche le formulaire et gère les erreurs d'authentification
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
        if ($this->getUser()) {
             return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError(); // Récupère l'erreur de connexion s'il y en a une
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername(); // Pré-remplit le champ email avec la dernière saisie

        // On crée aussi le formulaire d'inscription pour l'afficher sur la même page
        $form = $this->createForm(RegistrationFormType::class, new User());

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'registrationForm' => $form->createView()
        ]);
    }

    // Route de déconnexion : Symfony intercepte cette route via le firewall (security.yaml)
    // La méthode ne s'exécute jamais réellement, c'est le composant Security qui gère tout
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
