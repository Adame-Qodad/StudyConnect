<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant l'enregistrement (inscription) des nouveaux utilisateurs.
 */
class RegistrationController extends AbstractController
{
    /**
     * Affiche le formulaire d'inscription et traite la création d'un nouvel utilisateur.
     *
     * Si le formulaire est soumis et valide :
     * - Le mot de passe est haché
     * - Un rôle par défaut est attribué (ROLE_ELEVE)
     * - L'utilisateur est sauvegardé en base de données
     * - L'utilisateur est redirigé vers la page de connexion
     *
     * @param Request $request L'objet HTTP contenant la requête du client.
     * @param UserPasswordHasherInterface $passwordHasher Le service Symfony pour hasher les mots de passe.
     * @param EntityManagerInterface $em Le gestionnaire d'entité Doctrine pour interagir avec la base de données.
     *
     * @return Response La réponse HTTP avec le formulaire ou une redirection.
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $em
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1) Récupérer le mot de passe en clair depuis le formulaire
            $plain = $form->get('plainPassword')->getData();

            // 2) Hacher et définir le mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $plain));

            // 3) Définir le rôle par défaut
            $user->setRoles(['ROLE_ELEVE']);

            // 4) Définir le type par défaut
            $user->setTypeUtilisateur('Eleve');

            // 4) Persister et flush
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
