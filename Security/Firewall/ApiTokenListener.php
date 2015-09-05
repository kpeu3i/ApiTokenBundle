<?php

namespace Bukatov\ApiTokenBundle\Security\Firewall;

use Bukatov\ApiTokenBundle\ParameterFetcher\ParameterFetcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token;

class ApiTokenListener implements ListenerInterface
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
     * @var string
     */
    protected $tokenName;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, ParameterFetcherInterface $parameterFetcher, $tokenName)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->parameterFetcher = $parameterFetcher;
        $this->tokenName = $tokenName;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $this->parameterFetcher->fetch($request, $this->tokenName);

        if ($token === null) {
            return;
        }

        $apiToken = new Token\ApiToken($token);

        try {
            $authToken = $this->authenticationManager->authenticate($apiToken);
            $this->tokenStorage->setToken($authToken);
        } catch (AuthenticationException $failed) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $response->setContent('Authentication failed');
            $event->setResponse($response);
        }
    }
}