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

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce/index", name="annonce_index")
     * @param AnnonceRepository $repository
     * @return Response
     */
    public function index(AnnonceRepository $repository)
    {
        $annonces = $repository->findAll();

        return $this->render('annonce/index.html.twig',
            [
            'annonces' => $annonces
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/annonce/new" , name="annonce_new")
     */
    public function new(Request $request,EntityManagerInterface $entityManager)
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class,$annonce);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            foreach($annonce->getImages() as $image)
            {
                $image->setAnnonce($annonce);
                $entityManager->persist($image);
            }
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success',
                'Votre annonce <strong>'.$annonce->getTitle().'</strong> a bien été enregistrée !');

            return $this->redirectToRoute('annonce_show',[
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('annonce/new.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet d'afficher une annonce
     * @Route("/annonce/{slug}" , name="annonce_show")
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce)
    {
        //je récupère l'annonce qui correspond aux slug

        return $this->render('annonce/show.html.twig',[
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/annonce/{slug}/edit/", name="annonce_edit")
     * @param Request $request
     * @param Annonce $annonce
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request,Annonce $annonce,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AnnonceType::class,$annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($annonce->getImages() as $image)
            {
                $image->setAnnonce($annonce);
                $entityManager->persist($image);
            }
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success',
                'Les modifications de l\'annonce <strong>'.$annonce->getTitle().'</strong> ont bien été enregistrée !');

            return $this->redirectToRoute('annonce_show',[
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('annonce/edit.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
}
