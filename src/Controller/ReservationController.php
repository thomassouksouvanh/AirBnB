<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/annonce/{slug}/reservation", name="reservation_create")
     * @param Annonce $annonce
     * @return Response
     */
    public function index(Annonce $annonce)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$reservation);

        return $this->render('reservation/reservation.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()

        ]);
    }
}
