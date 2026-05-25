<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// cette classe sert à mettre des données de test dans la base
// on la lance avec : php bin/console doctrine:fixtures:load
class AppFixtures extends Fixture
{
    private $passwordHasher; // pour hasher le mot de passe

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // on crée un utilisateur admin pour tester
        $user = new User();
        $user->setEmail('chaymae@boutique.com');
        $user->setFirstName('Chaymae');
        $user->setLastName('Barhdadi');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin123'));
        $manager->persist($user);

        // les catégories qu'on va utiliser
        $categories = [
            'High-Tech'     => 'high-tech',
            'Vêtements'     => 'vetements',
            'Maison'        => 'maison',
            'Bien-être'     => 'bien-etre',
            'Accessoires'   => 'accessoires'
        ];

        // on boucle pour créer chaque catégorie et on les garde dans un tableau
        $categoryEntities = [];
        foreach ($categories as $name => $slug) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $categoryEntities[$name] = $category;
        }

        // liste des produits avec toutes leurs infos
        $products = [
            [
                'name'        => 'Casque Audio Nomad X3',
                'price'       => 89.99,
                'category'    => 'High-Tech',
                'image'       => 'casque.jpg',
                'description' => 'Un casque léger avec réduction de bruit passive et son clair. Idéal pour les trajets quotidiens, autonomie de 20h.'
            ],
            [
                'name'        => 'Souris Optique SilentClick',
                'price'       => 32.50,
                'category'    => 'High-Tech',
                'image'       => 'souris.jpg',
                'description' => 'Souris filaire silencieuse avec capteur optique 1600 DPI. Compatible Windows et Mac, prise en main agréable.'
            ],
            [
                'name'        => 'Enceinte Portable SoundBox',
                'price'       => 54.90,
                'category'    => 'High-Tech',
                'image'       => 'enceinte.jpg',
                'description' => 'Mini enceinte Bluetooth résistante aux éclaboussures. Connexion rapide, son puissant pour sa taille.'
            ],
            [
                'name'        => 'Veste Légère Outdoor',
                'price'       => 75.00,
                'category'    => 'Vêtements',
                'image'       => 'veste.jpg',
                'description' => 'Veste coupe-vent imperméable parfaite pour les sorties en plein air. Légère et pliable, elle tient dans un sac.'
            ],
            [
                'name'        => 'Pull Col Roulé Confort',
                'price'       => 38.00,
                'category'    => 'Vêtements',
                'image'       => 'pull.jpg',
                'description' => 'Pull chaud en maille douce, coupe classique et confortable pour l\'hiver. Disponible en plusieurs coloris.'
            ],
            [
                'name'        => 'Plante Succulente Trio',
                'price'       => 19.90,
                'category'    => 'Maison',
                'image'       => 'plante.jpg',
                'description' => 'Lot de 3 petites plantes succulentes faciles à entretenir. Parfaites pour décorer un bureau ou un rebord de fenêtre.'
            ],
            [
                'name'        => 'Lampe de Bureau LED',
                'price'       => 27.00,
                'category'    => 'Maison',
                'image'       => 'lampe.jpg',
                'description' => 'Lampe flexible avec 3 niveaux de luminosité, port USB intégré pour charger son téléphone en même temps.'
            ],
            [
                'name'        => 'Tapis de Yoga Confort',
                'price'       => 41.00,
                'category'    => 'Bien-être',
                'image'       => 'tapis.jpg',
                'description' => 'Tapis antidérapant épais 6mm, surface texturée pour une bonne adhérence. Léger et facile à enrouler.'
            ],
            [
                'name'        => 'Bougie Parfumée Vanille',
                'price'       => 14.50,
                'category'    => 'Bien-être',
                'image'       => 'bougie.jpg',
                'description' => 'Bougie naturelle à la cire de soja avec parfum vanille douce. Durée de combustion estimée à 40 heures.'
            ],
            [
                'name'        => 'Pochette Organiseur Voyage',
                'price'       => 16.00,
                'category'    => 'Accessoires',
                'image'       => 'pochette.jpg',
                'description' => 'Pochette compacte avec plusieurs compartiments pour ranger câbles, chargeurs et petits accessoires en voyage.'
            ],
            [
                'name'        => 'Carnet Bullet Journal A5',
                'price'       => 11.90,
                'category'    => 'Accessoires',
                'image'       => 'carnet.jpg',
                'description' => 'Carnet à points 160 pages, couverture rigide. Idéal pour organiser ses tâches, dessiner ou prendre des notes.'
            ],
        ];

        // on crée chaque produit et on l'associe à sa catégorie
        foreach ($products as $pData) {
            $product = new Product();
            $product->setName($pData['name']);
            $product->setPrice($pData['price']);
            $product->setImage($pData['image']);
            $product->setDescription($pData['description']);
            $product->setCategory($categoryEntities[$pData['category']]);
            $manager->persist($product);
        }

        // on envoie tout en base d'un coup
        $manager->flush();
    }
}
