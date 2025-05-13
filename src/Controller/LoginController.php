<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur gérant l'authentification des utilisateurs.
 */
class LoginController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et gère les erreurs d'authentification.
     *
     * Si l'utilisateur est déjà connecté, il est redirigé vers la page d'accueil.
     * Sinon, le formulaire de connexion est affiché avec les éventuelles erreurs et
     * le dernier identifiant saisi.
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaire pour gérer les erreurs de connexion et récupérer le dernier identifiant saisi.
     *
     * @return Response La réponse HTTP contenant le rendu du formulaire de connexion.
     */
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Récupère l'erreur de connexion (si présente)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier email de l'utilisateur (si présent)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
