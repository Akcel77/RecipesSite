<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    /**
     * @param Generator $faker
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {

        //Ingredients
        $ingredients = [];
        for ($i = 1; $i <= 50 ; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));

            $ingredients[] = $ingredient ;
            $manager->persist($ingredient);
        }

        //Recipes
        for ($i = 1; $i <= 25 ; $i++){
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) === 1 ? true : false)
                ->setDescription($this->faker->text(300));

            for ($j = 0 ; $j < mt_rand(5,15); $j++){
                $recipe->addIngredient(($ingredients[mt_rand(0, count($ingredients) - 1 )]));
            }

            $manager->persist($recipe);
        }

        //User
        for ($i = 1; $i <= 10 ; $i++){
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setFisrtname($this->faker->firstName())
                ->setName($this->faker->lastName())
                ->setPseudo($this->faker->userName());
            $hash = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($hash);


            $manager->persist($user);
        }

        $manager->flush();
    }
}
