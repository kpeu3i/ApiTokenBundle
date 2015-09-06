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
    private $hideUserNotFoundExceptions;

    public function __construct(UserCheckerInterface $userChecker, EncoderFactoryInterface $encoderFactory, UserProviderFactory $userProviderFactory, $hideUserNotFoundExceptions = true)
    {
        $this->userChecker = $userChecker;
        $this->encoderFactory = $encoderFactory;
        $this->userProviderFactory = $userProviderFactory;
        $this->hideUserNotFoundExceptions = $hideUserNotFoundExceptions;
    }

    public function createGetApiTokenProvider($userClass)
    {
        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider($userClass);

        return new GetApiTokenProvider($userProvider, $this->userChecker, $this->encoderFactory, $this->hideUserNotFoundExceptions);
    }

    public function createApiTokenProvider($userClass)
    {
        $userProvider = $this->userProviderFactory->createEntityApiTokenUserProvider($userClass);

        return new ApiTokenProvider($userProvider);
    }
}