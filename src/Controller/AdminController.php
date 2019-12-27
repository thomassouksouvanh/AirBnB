<?php

namespace App\Controller;


use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/annonces", name="admin_index_annonce")
     * @param AnnonceRepository $repository
     * @return Response
     */
    public function index(AnnonceRepository $repository)
    {
        return $this->render('admin/index_annonce.html.twig', [
            'annonces' => $repository->findAll()
        ]);
    }
}
