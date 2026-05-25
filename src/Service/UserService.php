<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// service qui gère l'inscription des utilisateurs
// j'ai mis ça dans un service pour pas surcharger le controller
class UserService
{
    private $entityManager;  // pour sauvegarder en base
    private $passwordHasher; // pour hasher le mot de passe avant de le stocker

    // Symfony injecte les dépendances automatiquement
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    // enregistre un nouvel utilisateur dans la base de données
    public function registerUser(User $user, string $plainPassword): void
    {
        // on hash le mot de passe avant de le stocker, jamais en clair
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $this->entityManager->persist($user); // on prépare l'insertion
        $this->entityManager->flush();        // on envoie en base
    }
}
