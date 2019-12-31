<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Comment;
use App\Entity\Reservation;
use App\Form\CommentType;
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

            // si les dates ne sont pas disponible, message d'erreur
            if (!$reservation->isReservationDates()) {
                $this->addFlash(
                    'warning',
                    'Les dates que vous avez choisi ne peuvent être réservé: elles sont déjà prises'
                );
            } else {
                // sinon enrengistrement et redirection

                $entityManager->persist($reservation);
                $entityManager->flush();

                //envoi un parametre en GET pour afficher le message d'allert si reservation réussie
                return $this->redirectToRoute('reservation_show',
                    ['id' => $reservation->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('reservation/reservation.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()

        ]);
    }

    /**
     * affiche une reservation
     * @Route("/reservation/{id}" ,name="reservation_show")
     * @param Reservation $reservation
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(Reservation $reservation,Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setAnnonce($reservation->getAnnonce())
                    ->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success','Votre avis a bien été pris en compte');

            return $this->redirectToRoute('account_reservation');
        }
        return $this->render('reservation/show.html.twig',
            [
                'reservation' => $reservation,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/reservation/{id}/edit", name="reservation_edit")
     * @param Reservation $reservation
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Reservation $reservation,Request $request,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
         $entityManager->persist($reservation);
         $entityManager->flush();

         $this->addFlash(
             'success',
             'Votre réservation a été bien modifiée');

         return $this->redirectToRoute('account_reservation');
        }
        return $this->render('reservation/edit.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation

            ]);
    }

    /**
     * @param Reservation $reservation
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/reservation/{id}/delete", name="reservation_delete")
     */
    public function delete(Reservation $reservation,EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash(
            'error',
            'Votre réservation est a présent supprimée'
        );

        return $this->redirectToRoute('account_reservation');
    }
}
