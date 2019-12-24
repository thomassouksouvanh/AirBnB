<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/annonce/{slug}/reservation", name="reservation_create")
     * @param Annonce $annonce
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function create(Annonce $annonce,Request $request,EntityManagerInterface $entityManager)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$reservation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();

            $reservation->setClient($user)
                        ->setAnnonce($annonce);
            $entityManager->persist($reservation);
            $entityManager->flush();

            //envoi un parametre en GET pour afficher le message d'allert si reservation réussie
            return  $this->redirectToRoute('reservation_show',['id'=>$annonce->getId(),
                'withAlert'=> true ]);
        }

        return $this->render('reservation/reservation.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()

        ]);
    }

    /**
     * Permet d'afficher une reservation
     * @Route("/reservation/{id}" ,name="reservation_show")
     * @param Reservation $reservation
     * @return Response
     */
    public function show(Reservation $reservation)
    {
        return $this->render('reservation/show.html.twig',['reservation' => $reservation]);
    }
}
