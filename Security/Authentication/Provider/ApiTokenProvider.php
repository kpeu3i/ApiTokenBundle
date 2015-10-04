<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Bukatov\ApiTokenBundle\ApiToken\Manager\ApiTokenManagerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\Token;
use Bukatov\ApiTokenBundle\Security\Core\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiTokenProvider implements AuthenticationProviderInterface
{
    /**
     * @var ApiTokenManagerInterface
     */
    protected $apiTokenManager;

    /**
     * @var ApiTokenUserProviderInterface
     */
    protected $userProvider;

    public function __construct(ApiTokenManagerInterface $apiTokenManager, UserProviderInterface $userProvider)
    {
        $this->apiTokenManager = $apiTokenManager;
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        /* @var ApiTokenInterface $apiToken */
        $apiToken = $this->apiTokenManager->findByToken($token->getUser());

        if ($apiToken) {
            $user = $this->userProvider->loadUserByUsername($apiToken->getUsername());

            if ($user) {
                $authenticatedToken = new Token($user, $apiToken);

                return $authenticatedToken;
            }
        }

//        /* @var Token $token */
//        $apiToken = $this->userProvider->loadApiTokenByValue($token->getUser());
//        $user = $apiToken->getUser();
//
//        if ($apiToken && $apiToken->isValid($token->getIpAddress())) {
//            $authenticatedToken = new Token($user, $user->getRoles(), $apiToken);
//            //$this->userProvider->refreshApiTokenLastUsedAt($apiToken);
//
//            return $authenticatedToken;
//        }

        throw new AuthenticationException();
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof Token && $this->userProvider instanceof ApiTokenUserProviderInterface;
    }
}