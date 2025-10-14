<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Ingredients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom($faker->word());
            $ingredient->setPrix($faker->randomFloat(2, 2, 200));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recettes
        for ($j = 0; $j < 25; $j++) {
            $recette = new Recette();
            $recette->setNom($faker->sentence(4));
            $recette->setTemps($faker->numberBetween(10, 1440));
            $recette->setNbPersonnes($faker->numberBetween(1, 50));
            $recette->setDifficulte($faker->numberBetween(1, 5));
            $recette->setDescription($faker->text(300));
            $recette->setPrix($faker->randomFloat(2, 20, 1000));
            $recette->setIsFavorite($faker->boolean());

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recette);
        }

        $manager->flush();
    }
}
