<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\ApiToken\Manager\ApiTokenManagerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\LoginToken;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\SecureToken;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginProvider extends DaoAuthenticationProvider
{
    /**
     * @var ApiTokenManagerInterface
     */
    protected $apiTokenManager;

    /**
     * @var int
     */
    protected $tokenAbsoluteLifetime = 0;

    /**
     * @var int
     */
    protected $tokenInactiveLifetime = 0;

    public function __construct(
        ApiTokenManagerInterface $apiTokenManager,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        EncoderFactoryInterface $encoderFactory,
        $hideUserNotFoundExceptions = true
    )
    {
        $this->apiTokenManager = $apiTokenManager;
        $providerKey = uniqid(time(), true);

        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);
    }

    public function authenticate(TokenInterface $token)
    {
        /* @var LoginToken $token */
        $usernamePasswordToken = parent::authenticate($token);

        $apiToken = $this->apiTokenManager->create($usernamePasswordToken->getUser(), $token->getIpAddress(), $this->tokenAbsoluteLifetime);

        $this->apiTokenManager->save($apiToken->getToken(), $apiToken, $this->tokenInactiveLifetime);

        return new SecureToken($usernamePasswordToken->getUser(), $apiToken);
    }

    public function setTokenAbsoluteLifetime($lifetime)
    {
        $this->tokenAbsoluteLifetime = intval($lifetime);
    }

    public function setTokenInactiveLifetime($lifetime)
    {
        $this->tokenInactiveLifetime = intval($lifetime);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof LoginToken;
    }
}