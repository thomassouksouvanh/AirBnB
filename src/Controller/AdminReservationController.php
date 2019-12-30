<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservation", name="admin_reservation")
     */
    public function index()
    {
        return $this->render('admin_reservation/index.html.twig', [
            'controller_name' => 'AdminReservationController',
        ]);
    }
}
