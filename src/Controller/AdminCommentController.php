<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment")
     */
    public function index()
    {
        return $this->render('admin_comment/index.html.twig', [
            'controller_name' => 'AdminCommentController',
        ]);
    }
}
