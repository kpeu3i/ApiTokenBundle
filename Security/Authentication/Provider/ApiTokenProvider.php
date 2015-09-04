<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token;
use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiTokenProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        /* @var ApiTokenUserProviderInterface $userProvider */
        $userProvider = $this->userProvider;

        $apiTokenHash = $token->getUser();
        $user = $userProvider->loadUserByApiToken($apiTokenHash);
        $apiToken = $user ? $user->getApiToken() : null;

        if ($apiToken && $apiToken->isValid()) {
            $authenticatedToken = new Token\ApiToken($user->getRoles());
            $authenticatedToken->setUser($user);

            $userProvider->refreshApiTokenLastUsedAtForUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException();
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof Token\ApiToken && $this->userProvider instanceof ApiTokenUserProviderInterface;
    }
}