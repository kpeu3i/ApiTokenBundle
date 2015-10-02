<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiToken;
use Bukatov\ApiTokenBundle\Security\Core\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token;
use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiTokenProvider implements AuthenticationProviderInterface
{
    /**
     * @var ApiTokenUserProviderInterface
     */
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        /* @var ApiToken $token */
        $apiToken = $this->userProvider->loadApiTokenByValue($token->getUser());
        $user = $apiToken->getUser();

        if ($apiToken && $apiToken->isValid($token->getIpAddress())) {
            $authenticatedToken = new Token\ApiToken($user, $user->getRoles(), $apiToken);
            //$this->userProvider->refreshApiTokenLastUsedAt($apiToken);

            return $authenticatedToken;
        }

        throw new AuthenticationException();
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof Token\ApiToken && $this->userProvider instanceof ApiTokenUserProviderInterface;
    }
}