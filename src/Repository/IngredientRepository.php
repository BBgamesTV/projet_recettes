<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }
    //    /**
    //     * @return Ingredient[] Returns an array of Ingredient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ingredient
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findIngredientTomate(): array
    {
        return $this->createQueryBuilder('i')   // 'i' est un alias pour Ingredient
            ->andWhere('i.nom = :nom')
            ->setParameter('nom', 'Tomate')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findIngredientTomate5(): array
    {
        return $this->createQueryBuilder('i')   // 'i' est un alias pour Ingredient
            ->andWhere('i.nom = :nom')
            ->andWhere('i.prix > :prix')
            ->setParameter('nom', 'Tomate')
            ->setParameter('prix', '5')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findIngredientByPrice(float $price): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.prix >= :price')
            ->setParameter('price', $price)
            ->orderBy('i.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findIngredientByPriceByName(float $price,string $name): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.prix = :price')
            ->andWhere('i.nom = :name')
            ->setParameter('price', $price)
            ->setParameter('name', $name)
            ->orderBy('i.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
