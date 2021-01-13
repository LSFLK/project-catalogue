<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
	    $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'google_auth';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $userRepository = $this->entityManager->getRepository(User::class);

        // 1) If the user has logged in with Google before.
        $user = $userRepository->findOneBy(['google_id' => $googleId]);
        
        if (!$user) {
            // 2) If there exists a matching user by email.
            $user = $userRepository->findOneBy(['email' => $email]);

            if(!$user) {
                $user = new User();
                $user->setGoogleId($googleId);
                $user->setEmail($email);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }
        
        return $userProvider->loadUserByUsername($user->getEmail());
    }

    private function getGoogleClient() : GoogleClient
    {
        return $this->clientRegistry->getClient('google');
	}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate('projects');
        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $messageKey = $exception->getMessageKey();
        $messageData = $exception->getMessageData();
        $message = strtr($messageKey, $messageData);

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/signin/', Response::HTTP_TEMPORARY_REDIRECT);
    }
}