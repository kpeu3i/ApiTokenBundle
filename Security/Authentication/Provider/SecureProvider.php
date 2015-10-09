<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Bukatov\ApiTokenBundle\ApiToken\Manager\ApiTokenManagerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\TransportToken;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\SecureToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecureProvider implements AuthenticationProviderInterface
{
    /**
     * @var ApiTokenManagerInterface
     */
    protected $apiTokenManager;

    /**
     * @var int
     */
    protected $tokenInactiveLifetime = 0;

    public function __construct(ApiTokenManagerInterface $apiTokenManager, UserProviderInterface $userProvider)
    {
        $this->apiTokenManager = $apiTokenManager;
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        /**
         * @var TransportToken $token
         * @var ApiTokenInterface $apiToken
         */
        $apiToken = $this->apiTokenManager->find($token->getValue());

        if ($apiToken) {
            $user = $this->userProvider->loadUserByUsername($apiToken->getUsername());
            if ($user && $this->apiTokenManager->isValid($apiToken)) {
                $apiToken->refreshLastUsedAt();
                $this->apiTokenManager->save($apiToken->getToken(), $apiToken, $this->tokenInactiveLifetime);

                return new SecureToken($user, $apiToken);
            }
        }

        throw new AuthenticationException();
    }

    public function setTokenInactiveLifetime($lifetime)
    {
        $this->tokenInactiveLifetime = intval($lifetime);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof TransportToken;
    }
}