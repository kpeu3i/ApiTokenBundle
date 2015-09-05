<?php

namespace Bukatov\ApiTokenBundle\Security\Core;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager as BaseAuthenticationProviderManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationManager extends BaseAuthenticationProviderManager
{
    /**
     * @var AuthenticationManagerInterface
     */
    protected $manager;

    /**
     * @var array
     */
    protected $providers;

    /**
     * @var bool
     */
    protected $eraseCredentials;

    /**
     * @var EventDispatcherInterface|null
     */
    protected $eventDispatcher;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(array $providers = [], $eraseCredentials = true)
    {
        $this->providers = $providers;
        $this->eraseCredentials = (bool)$eraseCredentials;
    }

    public function authenticate(TokenInterface $token)
    {
        return $this->getManager()->authenticate($token);
    }

    public function authenticateAndStore(TokenInterface $token)
    {
        $authenticatedToken = $this->getManager()->authenticate($token);

        $this->tokenStorage->setToken($authenticatedToken);

        return $authenticatedToken;
    }

    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->getManager()->setEventDispatcher($dispatcher);
    }

    protected function getManager()
    {
        if ($this->manager === null) {

            if (!$this->providers) {
                throw new \InvalidArgumentException('You must at least add one authentication provider.');
            }

            foreach ($this->providers as $provider) {
                if (!$provider instanceof AuthenticationProviderInterface) {
                    throw new \InvalidArgumentException(sprintf('Provider "%s" must implement the AuthenticationProviderInterface.', get_class($provider)));
                }

                print_r(get_class($provider));
                print_r("\n");
            }

            $this->manager = new BaseAuthenticationProviderManager($this->providers, $this->eraseCredentials);
        }

        return $this->manager;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param array $providers
     *
     * @return $this
     */
    public function setProviders(array $providers)
    {
        $this->providers = $providers;

        $this->manager = null;

        return $this;
    }

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return bool
     */
    public function getEraseCredentials()
    {
        return $this->eraseCredentials;
    }

    /**
     * @param mixed $eraseCredentials
     *
     * @return $this
     */
    public function setEraseCredentials($eraseCredentials)
    {
        $this->eraseCredentials = (bool)$eraseCredentials;

        $this->manager = null;

        return $this;
    }
}