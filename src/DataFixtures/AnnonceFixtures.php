<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AnnonceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('FR-fr');

        //gestion des utilisateurs

        $users=[];


        for($i = 1 ; $i < 17 ; $i++)
        {

            $user = new User();

            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setIntroduction($faker->sentence());
            $user->setDescription('<p>'.join('</p> <p>',$faker->paragraphs(3)).'</p>');
            $user->setHash('password');

            $manager->persist($user);
            $users[] = $user;
        }

        //gestion des annonces
        for($i = 0 ; $i < 46 ; $i++)
        {
            $annonce = new Annonce();
            $title = $faker->sentence();
            $photoCover = $faker->image($width = 800, $height = 580);
            $introduction = $faker->paragraph(5);
            $content = '<p>'.join('</p> <p>',$faker->paragraphs(6)).'</p>';

            $user = $users[mt_rand(0,count($users)-1)];

            $annonce->setTitle($title)
                    ->setPhotoCover($photoCover)
                    ->setIntroduction($introduction)
                    ->setContent($content)
                    ->setPrice(mt_rand(25,300))
                    ->setRooms(mt_rand(1,4))
                    ->setAuthor($user);

            for($j = 1 ; $j <= mt_rand(3,9) ; $j++ )
            {
                $image = new Image();
                $image->setPhoto($faker->image($width = 600, $height = 380))
                    ->setCaption($faker->sentence())
                    ->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
