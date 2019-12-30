<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * Afficher liiste des commentaires
     * @Route("/admin/comments", name="admin_comments")
     * @param CommentRepository $repository
     * @return Response
     */
    public function index(CommentRepository $repository)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repository->findAll()
        ]);
    }

    /**
     * Permet d'éditer les commentaires
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Comment $comment,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AdminCommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le commentaire <strong>'.$comment->getId().'</strong> est a présent modifié');

            return $this->redirectToRoute('admin_comments');
        }

        return $this->render('admin/comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comment,
            ]);
    }

    /**
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function delete(Comment $comment,EntityManagerInterface $entityManager)
    {
        $entityManager->remove($comment);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Le commentaire <strong>'.$comment->getAuthor()->getFullName().'</strong> à été supprimer'
        );

        return $this->redirectToRoute('admin_comments');
    }
}
