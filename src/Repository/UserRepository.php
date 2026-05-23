<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// UserRepository : repository Doctrine pour l'entité User
// Implémente PasswordUpgraderInterface pour permettre la mise à jour automatique des hashs de mots de passe
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    // Le constructeur lie ce repository à l'entité User via le ManagerRegistry
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    // Méthode appelée automatiquement par Symfony lorsque l'algorithme de hashage change
    // Elle rehash et sauvegarde le nouveau mot de passe pour l'utilisateur connecté
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            // On vérifie que l'objet passé est bien une instance de User, sinon on lève une exception
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword); // Mise à jour du mot de passe hashé
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush(); // Sauvegarde en base de données
    }
}
