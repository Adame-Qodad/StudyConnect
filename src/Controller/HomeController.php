<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur principal de l'application.
 *
 * Ce contrôleur gère la page d'accueil accessible à la racine du site.
 */
final class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil.
     *
     * Cette méthode est associée à la route "/" et utilise le nom de route "app_home".
     * Elle rend la vue Twig située dans "home/index.html.twig".
     *
     * @return Response La réponse HTTP contenant le rendu de la page d'accueil.
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
