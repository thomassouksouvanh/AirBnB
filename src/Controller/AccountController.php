<?php

namespace App\Controller;

use App\Entity\UpdatePassword;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/account/registration", name="account_registrer")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function registration(Request $request,EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form= $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash =$encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash
            (
                'success',
                'Votre compte a bien été enregistrer,Vous pouvez vous connecter'
            );


            return $this->redirectToRoute('account_login');
        }

            return $this->render('account/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/account/profile",name="account_profile")
     * Modification des informations du profile
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();//recupérer l'ID du user
        $form = $this->createForm(AccountType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success','Vos modifications ont bien été pris en compte');
        }

        return $this->render('account/profile.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet de modifier le mot de passe
     * @Route("/account/updatepassword",name="account_update_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $updatePassword = new UpdatePassword();
        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class,$updatePassword);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // vérifié que le oldpassword est le bon par rapport au user
            if(!password_verify($updatePassword->getOldPassword(),$user->getHash())){
            // gerer l'erreur
                $form->get('oldPassword')->addError(new FormError('Le mot de passe est incorrect'));

        }else{
                $newPassword = $updatePassword->getNewPassword();
                $hash = $encoder->encodePassword($user,$newPassword);

                $user->setHash($hash);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success','Votre nouveau mot de passe a bien été modifié');

                return $this->redirectToRoute('account_profile');
            }
        }
        return $this->render('account/updatepassword.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet d'affichere le profil de l'user connecté
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     */
    public function Account()
    {
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }

    /**
     * permet d'afficher les reservations du User
     * @Route("/account/reservation", name="account_reservation")
     */
    public function reservation()
    {
        return $this->render('account/reservation.html.twig');
    }


    /**
     * @Route("/account/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     *
     * @Route("/account/logout", name="account_logout")
     */
    public function logout()
    {
    }
}
