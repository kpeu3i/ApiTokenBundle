<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token;
use Bukatov\ApiTokenBundle\Entity;

class ApiTokenProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(ApiTokenUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $apiTokenHash = $token->getUser();
        $user = $this->userProvider->loadUserByApiToken($apiTokenHash);
        $apiToken = $user ? $user->getApiToken() : null;

        if ($apiToken && $apiToken->isValid()) {
            $authenticatedToken = new Token\ApiToken($user->getRoles());
            $authenticatedToken->setUser($user);

            $this->userProvider->refreshApiTokenLastUsedAtForUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException();
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof Token\ApiToken;
    }
}