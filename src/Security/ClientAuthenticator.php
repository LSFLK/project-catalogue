<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClientAuthenticator extends SocialAuthenticator
{
    private $authProviders = ['google'];
    private $client;

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
        $route = $request->attributes->get('_route');
        
        foreach($this->authProviders as $authProvider) {
            if($route === $authProvider.'_auth') {
                $this->client = $authProvider;
                return true;
            }
        }
    }

    private function getClient()
    {
        return $this->clientRegistry->getClient($this->client);
	}

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $authUser = $this->getClient()->fetchUserFromToken($credentials);
        $user = $this->entityManager->getRepository(User::class)->findUserIfExists($authUser, $this->client);
        
        if (!$user) {
            $user = new User();
            $user->setEmail($authUser->getEmail());

            $clientIdSetter = 'set'.ucfirst($this->client).'Id';
            $user->$clientIdSetter($authUser->getId());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        
        return $userProvider->loadUserByUsername($user->getEmail());
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