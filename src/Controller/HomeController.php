<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_index")
     * @param AnnonceRepository $repository
     * @return Response
     */
    public function index(AnnonceRepository $repository)
    {
        $annonces = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
