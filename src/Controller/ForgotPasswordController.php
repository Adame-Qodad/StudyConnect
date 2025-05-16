<?php
// src/Controller/ForgotPasswordController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant la demande de réinitialisation de mot de passe.
 */
class ForgotPasswordController extends AbstractController
{
    /**
     * Affiche le formulaire de demande et envoie le token de réinitialisation par email.
     *
     * @param Request                 $request      La requête HTTP
     * @param UserRepository          $users        Le repository User
     * @param EntityManagerInterface  $em           Le gestionnaire d’entité
     * @param MailService             $mailService  Service d’envoi d’emails
     *
     * @return Response
     *
     * @Route("/forgot-password", name="app_forgot_password", methods={"GET","POST"})
     */
    #[Route('/forgot-password', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    public function request(
        Request $request,
        UserRepository $users,
        EntityManagerInterface $em,
        MailService $mailService
    ): Response {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user  = $users->findOneBy(['email' => $email]);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $user->setPasswordResetToken($token);
                $user->setPasswordResetTokenExpiresAt(new \DateTime('+1 hour'));
                $em->flush();

                $mailService->sendPasswordResetEmail($user->getEmail(), $token);
            }

            $this->addFlash('info', 'Si cet email est enregistré, vous allez recevoir un lien de réinitialisation.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgot_password.html.twig', [
            'forgotForm' => $form->createView(),
        ]);
    }
}
