<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends AbstractController
{
    /**
     * @Route("/signin/google", name="google_signin")
     */
    public function signInWithGoogle(ClientRegistry $clientRegistry) : RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['profile', 'email'])
        ;
    }

    /**
     * @Route("/signin/facebook", name="facebook_signin")
     */
    public function signInWithFacebook(ClientRegistry $clientRegistry) : RedirectResponse
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect(['public_profile', 'email'])
        ;
    }

    /**
     * @Route("/signin/google/auth", name="google_auth")
     * @Route("/signin/facebook/auth", name="facebook_auth")
     */
    public function onAuthenticationSuccessWithClient(Request $request, ClientRegistry $clientRegistry) : RedirectResponse
    {
        return $this->redirectToRoute('projects');
    }
}