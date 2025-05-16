<?php
// src/Service/MailService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class MailService
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

   public function sendPasswordResetEmail(string $to, string $resetToken): void
{
    try {
        $email = (new Email())
            ->from('Study.Connect00@gmail.com')
            ->to($to)
            ->subject('Réinitialisation de votre mot de passe')
            ->html(
                '<p>Bonjour,</p>' .
                '<p>Vous avez demandé une réinitialisation de votre mot de passe.</p>' .
                '<p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>' .
                '<p><a href="http://127.0.0.1:8000/reset-password/' . $resetToken . '">Réinitialiser le mot de passe</a></p>'
            );

        $this->mailer->send($email);
    } catch (\Exception $e) {
        // Log l'erreur pour faciliter le diagnostic
        $this->logger->error('Erreur d\'envoi d\'email: ' . $e->getMessage());
    }
}
}
