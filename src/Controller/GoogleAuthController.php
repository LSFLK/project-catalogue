<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleAuthController extends AbstractController
{
    /**
     * @Route("/signin/google", name="google_signin")
     */
    public function connectAction(ClientRegistry $clientRegistry) : RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['profile', 'email'])
        ;
    }

    /**
     * @Route("/signin/google/auth", name="google_auth")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry) : RedirectResponse
    {
        return $this->redirectToRoute('projects');
    }
}