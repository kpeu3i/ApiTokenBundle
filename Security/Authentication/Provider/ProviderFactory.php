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

    public function __construct(UserCheckerInterface $userChecker, EncoderFactoryInterface $encoderFactory, UserProviderFactory $userProviderFactory)
    {
        $this->userChecker = $userChecker;
        $this->encoderFactory = $encoderFactory;
        $this->userProviderFactory = $userProviderFactory;
    }

    public function createGetApiTokenProvider($userClass, $providerKey = null, $hideUserNotFoundExceptions = true)
    {
        $providerKey = $providerKey === null ? uniqid(time(), true) : $providerKey;

        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider($userClass);

        return new GetApiTokenProvider($userProvider, $this->userChecker, $providerKey, $this->encoderFactory, $hideUserNotFoundExceptions);
    }

    public function createApiTokenProvider($userClass)
    {
        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider($userClass);

        return new ApiTokenProvider($userProvider);
    }
}