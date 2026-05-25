<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// entité User, représente un utilisateur dans l'appli
// elle implémente UserInterface pour que Symfony puisse gérer la connexion
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')] // user est un mot réservé en SQL donc on met des backticks
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // id auto généré

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null; // l'email sert aussi de login

    #[ORM\Column]
    private array $roles = []; // les rôles de l'utilisateur

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null; // mot de passe hashé, jamais en clair

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null; // prénom, optionnel

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null; // nom de famille, optionnel



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

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
    // Symfony utilise cette méthode pour identifier l'utilisateur, on retourne l'email
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // tout utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles); // on enlève les doublons
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }
}
