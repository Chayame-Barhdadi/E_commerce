<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// UserService : service dédié à la logique métier liée aux utilisateurs
// Il isole la logique d'enregistrement hors du contrôleur (principe de responsabilité unique)
class UserService
{
    private $entityManager;  // Gère la persistance des entités en base de données
    private $passwordHasher; // Hashage sécurisé des mots de passe

    // Injection des dépendances via le constructeur (autowiring Symfony)
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    // Enregistre un nouvel utilisateur : hashe son mot de passe puis le sauvegarde en BDD
    public function registerUser(User $user, string $plainPassword): void
    {
        // Le mot de passe en clair est hashé avant d'être stocké en base de données
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $this->entityManager->persist($user); // Prépare l'entité pour l'insertion
        $this->entityManager->flush();        // Exécute la requête SQL INSERT en BDD
    }
}
