<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Entité User : représente un utilisateur de l'application
// Implémente UserInterface (requis par le composant Security de Symfony)
// et PasswordAuthenticatedUserInterface (pour la gestion des mots de passe hashés)
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')] // Le nom 'user' est entre backticks car c'est un mot réservé SQL
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Clé primaire auto-incrémentée
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email unique servant d'identifiant de connexion
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Tableau des rôles de l'utilisateur (ex: ROLE_USER, ROLE_ADMIN)
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    // Mot de passe stocké sous forme hashée (jamais en clair en BDD)
    #[ORM\Column]
    private ?string $password = null;

    // Prénom de l'utilisateur (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    // Nom de famille de l'utilisateur (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;



    // Retourne l'identifiant unique de l'utilisateur
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne l'adresse email de l'utilisateur
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Définit l'adresse email de l'utilisateur
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    // Retourne l'identifiant unique utilisé par Symfony Security (ici l'email)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    // Retourne les rôles de l'utilisateur en s'assurant que ROLE_USER est toujours présent
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER'; // Chaque utilisateur possède au minimum ce rôle de base

        return array_unique($roles); // Supprime les doublons éventuels
    }

    // Définit les rôles de l'utilisateur
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    // Retourne le mot de passe hashé stocké en base de données
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Définit le mot de passe hashé (à ne jamais appeler avec un mot de passe en clair)
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    // Efface les données sensibles temporaires (ex: mot de passe en clair)
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // Retourne le prénom de l'utilisateur
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    // Définit le prénom (nullable)
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    // Retourne le nom de famille de l'utilisateur
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    // Définit le nom de famille (nullable)
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }
}
