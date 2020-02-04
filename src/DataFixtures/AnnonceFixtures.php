<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Reservation;
use App\Entity\Role;
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

        //creation admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);


        $adminUser = new User();
        $adminUser->setFirstName('Thomas')
            ->setLastName('Souksouvanh')
            ->setEmail('thomas.souksouvanh@sfr.fr')
            ->setIntroduction($faker->sentence())
            ->setDescription($faker->sentence())
            ->setHash($this->encoder->encodePassword($adminUser, 'root'))
            ->setPicture('https://drive.google.com/file/d/1e0pqOY-aLqb99GvdnbkOU_kPt_Lr_4Yk/view?usp=sharing')
            ->addUserRole($adminRole);

        $manager->persist($adminUser);

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 14; $i++) {

            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoder->encodePassword($user,'password');

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
        for ($i = 1; $i <= 25; $i++) {

            $annonce = new Annonce();

            $title        = $faker->sentence();
            $photoCover   = $faker->image();
            $introduction = $faker->paragraph(2);
            $content      = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $annonce->setTitle($title)
                ->setPhotoCover($photoCover)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
            $manager->persist($annonce);


            for ($j = 1; $j <= mt_rand(3, 9); $j++) {
                $image = new Image();
                $image->setPhoto($faker->image())
                    ->setCaption($faker->sentence())
                    ->setAnnonce($annonce);
                $manager->persist($image);
            }

            for ($j = 1; $j <= mt_rand(0, 7); $j++) {
                $reservation = new Reservation();
                $createdAt   = $faker->dateTimeBetween('-6 months');
                $startDate   = $faker->dateTimeBetween('-3 months');

                //gestion de la date de fin
                $duration = mt_rand(3, 10);
                $endDate  = (clone $startDate)->modify(("+$duration days"));

                $amount = $annonce->getPrice() * $duration;

                $client  = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $reservation->setClient($client)
                    ->setAnnonce($annonce)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);

                $manager->persist($reservation);

                //getstion des commentaires
                if (mt_rand(0, 1)) {
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                        ->setRating(mt_rand(1, 5))
                        ->setAuthor($client)
                        ->setAnnonce($annonce);
                    $manager->persist($comment);
                }
            }
        }
            $manager->flush();
    }

}
