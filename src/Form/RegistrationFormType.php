<?php

// src/Form/RegistrationFormType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, TextType, PasswordType};

/**
 * Formulaire de création d'un utilisateur (inscription).
 *
 * Ce formulaire permet de créer un utilisateur en saisissant son email, son nom, son prénom, son type
 * d'utilisateur (par défaut "Eleve"), et un mot de passe.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Construction du formulaire.
     *
     * @param FormBuilderInterface $b Le constructeur du formulaire
     * @param array $opts Les options passées au formulaire
     */
    public function buildForm(FormBuilderInterface $b, array $opts): void
    {
        $b
            ->add('email', EmailType::class, [
                'attr'=>['class'=>'form-control'],
            ])
            ->add('nomUtilisateur', TextType::class, [
                'label'=>'Nom', 'attr'=>['class'=>'form-control'],
            ])
            ->add('prenomUtilisateur', TextType::class, [
                'label'=>'Prénom','attr'=>['class'=>'form-control'],
            ])
            // champ désactivé, valeur par défaut "Eleve"
            ->add('typeUtilisateur', TextType::class, [
                'label'=>'Rôle','data'=>'Eleve','disabled'=>true,'attr'=>['class'=>'form-control'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped'=>false,
                'label'=>'Mot de passe',
                'attr'=>['class'=>'form-control'],
            ])
        ;
    }

    /**
     * Configure les options du formulaire.
     *
     * @param OptionsResolver $r Le résolveur d'options pour définir les options par défaut
     */
    public function configureOptions(OptionsResolver $r): void
    {
        $r->setDefaults(['data_class'=>User::class]);
    }
}
