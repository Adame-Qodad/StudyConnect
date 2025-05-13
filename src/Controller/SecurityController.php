<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur chargé de la gestion de la sécurité (authentification et déconnexion).
 */
class SecurityController extends AbstractController
{
    /**
     * Déconnecte l'utilisateur.
     *
     * Cette méthode ne contient pas de logique applicative car la gestion
     * de la déconnexion est prise en charge automatiquement par Symfony via le firewall.
     *
     * @return void
     *
     * @throws \LogicException Toujours levée car Symfony intercepte cette route.
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
        // Cette méthode est volontairement laissée vide.
        // Symfony intercepte cette route automatiquement via le firewall.
        throw new \LogicException('This method is intercepted by the firewall.');
    }
}
