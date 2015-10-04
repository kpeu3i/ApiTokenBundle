<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\ApiToken\ApiToken;
use Bukatov\ApiTokenBundle\ApiToken\Manager\ApiTokenManagerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\GetApiToken;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\Token;
use Bukatov\ApiTokenBundle\Security\Core\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GetApiTokenProvider extends DaoAuthenticationProvider
{
    /**
     * @var ApiTokenUserProviderInterface
     */
    private $userProvider;

    /**
     * @var int|null
     */
    private $lifetime;

    /**
     * @var int|null
     */
    private $idleTime;

    /**
     * @var ApiTokenManagerInterface
     */
    protected $apiTokenManager;

    public function __construct(
        ApiTokenManagerInterface $apiTokenManager,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        EncoderFactoryInterface $encoderFactory,
        $hideUserNotFoundExceptions = true
    )
    {
        $this->apiTokenManager = $apiTokenManager;
        $this->userProvider = $userProvider;
        $providerKey = uniqid(time(), true);

        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);
    }

    public function authenticate(TokenInterface $token)
    {
        /* @var GetApiToken $token */
        $usernamePasswordToken = parent::authenticate($token);

        $apiToken = new ApiToken();
        $apiToken
            ->setToken('22222222222')
            ->setUsername($usernamePasswordToken->getUser()->getUsername())
            ->setIpAddress($token->getIpAddress())
            ->init()
            ;

        $this->apiTokenManager->save($apiToken);

        $authenticatedToken = new Token($usernamePasswordToken->getUser(), $apiToken);

        return $authenticatedToken;
    }

    /**
     * @param mixed $lifetime
     *
     * @return $this
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * @param mixed $idleTime
     *
     * @return $this
     */
    public function setIdleTime($idleTime)
    {
        $this->idleTime = $idleTime;

        return $this;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof GetApiToken && $this->userProvider instanceof ApiTokenUserProviderInterface;
    }
}