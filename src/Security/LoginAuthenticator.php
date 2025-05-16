<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;  // ⬅️ bon namespace

class LoginAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UserProviderInterface $userProvider,
        private UserPasswordHasherInterface $passwordHasher,
        private RouterInterface $router
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() === '/login';
    }

    public function authenticate(Request $request): Passport
    {
        $post     = $request->request->all();
        $formData = $post['login_form'] ?? [];
        $email    = $formData['_username'] ?? '';
        $password = $formData['_password'] ?? '';

        if (!$email) {
            throw new AuthenticationException('Email manquant.');
        }

        return new SelfValidatingPassport(
            new UserBadge($email, fn($id) => $this->userProvider->loadUserByIdentifier($id)),
            [new PasswordCredentials($password)]  // ⬅️ utilisation correcte
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        $target = $this->getTargetPath($request->getSession(), $firewallName)
                  ?? $this->router->generate('app_home');
        return new RedirectResponse($target);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        // Stocke l’erreur dans une flash‑bag
        $request->getSession()
            ->getFlashBag()
            ->add('error', $exception->getMessageKey());

        return new RedirectResponse($this->router->generate('app_login'));
    }}
