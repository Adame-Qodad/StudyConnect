<?php
// src/Controller/ActivateController.php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant l’activation du compte utilisateur via token.
 */
class ActivateController extends AbstractController
{
    /**
     * Vérifie le token, active le compte et redirige vers la connexion.
     *
     * @param string                    $token    Le token d’activation reçu par email
     * @param UserRepository            $users    Le repository de l’entité User
     * @param EntityManagerInterface    $em       Le gestionnaire d’entité pour la mise à jour
     *
     * @return Response
     *
     * @Route("/activate/{token}", name="app_activate", methods={"GET"})
     */
    public function activate(
        string $token,
        UserRepository $users,
        EntityManagerInterface $em
    ): Response {
        $user = $users->findOneBy(['activationToken' => $token]);

        if (!$user || $user->getActivationTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('danger', 'Le lien d’activation est invalide ou a expiré.');
            return $this->redirectToRoute('app_register');
        }

        // Activation
        $user->setActivationToken(null);
        $user->setActivationTokenExpiresAt(null);
        // Ici on pourrait aussi ajouter un champ "enabled" à true
        $em->flush();

        $this->addFlash('success', 'Votre compte est activé ! Vous pouvez vous connecter.');
        return $this->redirectToRoute('app_login');
    }
}
