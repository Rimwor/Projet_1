<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     *
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Creation d\un nouvel objet Faker
        $faker = Factory::create('fr_FR');

        // Creation de nos 5 categories 
        // c = categorie
        for ($c =  0; $c < 5; $c++) {

            // Creation d'un nouvel objet Tag
            $tag = new Tag;

            // On ajout un nom a notre categorie
            $tag->setName($faker->colorName());

            // On fait persister les donnes
            $manager->persist($tag);
        }

        // On push les categories en BDD
        $manager->flush();

        // Recuperation des categories crees
        $tags = $manager->getRepository(Tag::class)->findAll();

        // Creation entre 15 et 30 taches aleatoirement
        // t = task
        for ($t = 0; $t < mt_rand(15, 30); $t++) {

            // Creation d'un nouvel objet Task
            $task = new Task;

            // On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime())
                // Attention les dates sont 
                // fonctions du parametrage du server
                ->setDueAt($faker->dateTimeBetween('now', '1 month'))
                ->setTag($faker->randomElement($tags));

            // On fait persister les donnes
            $manager->persist($task);
        }

        // Creation de 5 utilisateurs
        for ($u = 0; $u < 5; $u++) {

            // Creation d'un nouvel objet User
            $user = new User;

            // Hashage de notre mot de passe avec les parametres de securite de notre $
            // dans /config/packages/escurity.yml
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setPassword($hash);

            // Si premier utilisateur cree on lui donne le role d'admin
            if ($u === 0) {
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail("admin@admin.fr");
            } else {
                $user->setEmail($faker->safeEmail());
            }

            // On fait persister les donnees
            $manager->persist($user);
        }

        $manager->flush();
    }
}
