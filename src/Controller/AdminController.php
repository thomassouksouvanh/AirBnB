<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('admin/annonce/index.html.twig', [
            'annonces' => $repository->findAll()
        ]);
    }

    /**
     * Permet d'editer les annonces
     * @param Annonce $annonce
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return string
     * @Route("/admin/annonce/{id}/edit" , name="admin_annonce_edit")
     */
    public function edit(Annonce $annonce,Request $request, EntityManagerInterface $entityManager)
    {
        $form =$this->createForm(AnnonceType::class,$annonce);

        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid();
        {
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success','Les modifications ont bien été effectuées');
        }

        return $this->render('admin/annonce/edit.html.twig',
            [
                'annonce'=> $annonce,
                'form' => $form->createView()
            ]);
    }

    /**
     * @param Annonce $annonce
     * @param EntityManagerInterface $entityManager
     * @Route("/admin/{slug}/delete" ,name="admin_delete")
     * @return Response
     */
    public function delete(Annonce $annonce,EntityManagerInterface $entityManager)
    {
        if(count($annonce->getReservations()) > 0)
        {
            $this->addFlash(
                'warning',
                'Vous ne pouvez pas supprimer l\'annonce car il y a encore des réservations !');
        }else {
            $entityManager->remove($annonce);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'annonce <strong>' . $annonce->getTitle() . '</strong> a bien été supprimée');
        }
        return $this->redirectToRoute('admin_index_annonce');
    }
}
