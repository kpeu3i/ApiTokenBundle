<?php

namespace Bukatov\ApiTokenBundle\Security\Firewall;

use Bukatov\ApiTokenBundle\ParameterFetcher\ParameterFetcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class GetApiTokenListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var ParameterFetcherInterface
     */
    protected $parameterFetcher;

    /**
     * @var
     */
    protected $usernameParameter;

    /**
     * @var
     */
    protected $passwordParameter;

    /**
     * @var
     */
    protected $providerKey;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        $providerKey,
        ParameterFetcherInterface $parameterFetcher,
        $usernameParameter,
        $passwordParameter
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey = $providerKey;
        $this->parameterFetcher = $parameterFetcher;
        $this->usernameParameter = $usernameParameter;
        $this->passwordParameter = $passwordParameter;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $username = $this->parameterFetcher->fetch($request, $this->usernameParameter);
        $password = $this->parameterFetcher->fetch($request, $this->passwordParameter);

        if ($username === null) {
            return;
        }

        $token = new UsernamePasswordToken($username, $password, $this->providerKey);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);
        } catch (AuthenticationException $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $response->setContent($e->getMessage());
            $event->setResponse($response);
        }
    }
}