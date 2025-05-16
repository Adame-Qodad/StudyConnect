<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route('/politique-cookies', name: 'politique_cookies')]
        public function politiqueCookies(): Response
        {
            return $this->render('legal/politique_cookies.html.twig');
        }

}
