<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Creation d\un nouvel objet Faker
        $faker = Factory::create('fr_FR');

        // Creation entre 15 et 30 taches aleatoirement
        for ($t = 0; $t < mt_rand(15, 30); $t++) {

            // Creation d'un nouvel objet Task
            $task = new Task;

            // On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime())
                // Attention les dates sont 
                // fonctions du parametrage du server
                ->setDueAt($faker->dateTimeBetween('now', '1 month'));

            // On fait persister les donnes
            $manager->persist($task);
        }

        $manager->flush();
    }
}
