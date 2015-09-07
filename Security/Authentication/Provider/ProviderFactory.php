<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\Core\User\UserProviderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class ProviderFactory
{
    /**
     * @var UserCheckerInterface
     */
    protected $userChecker;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var UserProviderFactory
     */
    protected $userProviderFactory;

    /**
     * @var bool
     */
    protected $hideUserNotFoundExceptions;

    /**
     * @var null|int
     */
    protected $lifetime;

    /**
     * @var null|int
     */
    protected $idleTime;

    public function __construct(UserCheckerInterface $userChecker, EncoderFactoryInterface $encoderFactory, UserProviderFactory $userProviderFactory, $hideUserNotFoundExceptions = true, $lifetime = null, $idleTime = null)
    {
        $this->userChecker = $userChecker;
        $this->encoderFactory = $encoderFactory;
        $this->userProviderFactory = $userProviderFactory;
        $this->hideUserNotFoundExceptions = $hideUserNotFoundExceptions;
        $this->lifetime = $lifetime;
        $this->idleTime = $idleTime;
    }

    public function createGetApiTokenProvider()
    {
        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider();
        $provider = new GetApiTokenProvider($userProvider, $this->userChecker, $this->encoderFactory, $this->hideUserNotFoundExceptions);

        $provider
            ->setLifetime($this->lifetime)
            ->setIdleTime($this->idleTime);

        return $provider;
    }

    public function createApiTokenProvider()
    {
        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider();

        return new ApiTokenProvider($userProvider);
    }
}