<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AnnonceFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('FR-fr');

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 14; $i++) {

            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoderPassword($user,'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->sentence())
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        //gestion des annonces
        // Nous gérons les annonces
        for ($i = 1; $i <= 45; $i++) {

            $annonce = new Annonce();

            $title = $faker->sentence();
            $photoCover = $faker->imageURL(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $annonce->setTitle($title)
                ->setPhotoCover($photoCover)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
                $manager->persist($annonce);
            }

            for($j = 1 ; $j <= mt_rand(3,9) ; $j++ )
            {
                $image = new Image();
                $image->setPhoto($faker->imageUrl($width = 600, $height = 380))
                    ->setCaption($faker->sentence())
                    ->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->flush();
    }

}
