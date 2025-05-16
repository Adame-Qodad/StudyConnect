<?php
// src/Controller/ResetPasswordController.php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant la réinitialisation effective du mot de passe.
 */
class ResetPasswordController extends AbstractController
{
    /**
     * Affiche le formulaire de nouveau mot de passe et met à jour l’utilisateur.
     *
     * @param string                         $token         Le token de réinitialisation reçu par email
     * @param Request                        $request       La requête HTTP
     * @param UserRepository                 $users         Le repository User
     * @param UserPasswordHasherInterface    $passwordHasher Le service de hachage
     * @param EntityManagerInterface         $em            Le gestionnaire d’entité
     *
     * @return Response
     *
     * @Route("/reset-password/{token}", name="app_reset_password", methods={"GET","POST"})
     */
    #[Route('/reset-password/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function reset(
        string $token,
        Request $request,
        UserRepository $users,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        $user = $users->findOneBy(['passwordResetToken' => $token]);

        if (!$user || $user->getPasswordResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('danger', 'Le lien de réinitialisation est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgot_password');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plain = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plain));
            // Nettoie les tokens et date d’expiration
            $user->setPasswordResetToken(null);
            $user->setPasswordResetTokenExpiresAt(null);
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
