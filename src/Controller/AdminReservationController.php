<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\AdminReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="admin_reservation_index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
        $data = $this->getDoctrine()->getRepository(Reservation::class)->findBy([],[]);

        $reservations = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('admin/reservation/index.html.twig', [
            'reservations' => $reservations
        ]);
    }

    /**
     * @param Reservation $reservation
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/admin/reservation/{id}/edit", name="admin_reservation_edit")
     */
    public function edit(Reservation $reservation,Request $request,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AdminReservationType::class,$reservation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $reservation->setAmount(0);
            $entityManager->persist($reservation); // pas obligé si existe deja
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La réservation <strong>'.$reservation->getId().'</strong> est à présent modifiée');

            return $this->redirectToRoute('admin_reservation_index');
        }

        return $this->render('admin/reservation/edit.html.twig',
            [
                'reservation' => $reservation,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/admin/reservation/{id}/delete", name="admin_reservation_delete")
     * @param Reservation $reservation
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function delete(Reservation $reservation,EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash(
            'succes',
            'La reservation <strong>'.$reservation->getClient()->getFullName().'</strong> a été supprimer'
        );

        return $this->redirectToRoute('admin_reservation_index');
    }
}
