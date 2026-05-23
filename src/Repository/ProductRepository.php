<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
// ProductRepository : repository Doctrine pour l'entité Product
// Hérite de ServiceEntityRepository qui fournit les méthodes de base :
// find(), findAll(), findBy(), findOneBy()
class ProductRepository extends ServiceEntityRepository
{
    // Le constructeur passe la classe Product au parent pour que Doctrine sache quelle entité gérer
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
}
