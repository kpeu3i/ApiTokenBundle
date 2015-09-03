<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class GetApiTokenProvider extends DaoAuthenticationProvider
{
    private $userProvider;

    private $lifetime;

    private $idleTime;

    public function __construct(ApiTokenUserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, EncoderFactoryInterface $encoderFactory, $hideUserNotFoundExceptions = true)
    {
        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);

        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $authenticatedToken = parent::authenticate($token);

        $this->userProvider->createOrUpdateApiTokenForUser($authenticatedToken->getUser(), $this->lifetime, $this->idleTime);

        return $authenticatedToken;
    }

    /**
     * @param mixed $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @param mixed $idleTime
     */
    public function setIdleTime($idleTime)
    {
        $this->idleTime = $idleTime;
    }
}