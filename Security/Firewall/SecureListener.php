<?php

namespace Bukatov\ApiTokenBundle\Security\Firewall;

use Bukatov\ApiTokenBundle\RequestParamFetcher\RequestParamFetcherInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\TransportToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token;

class SecureListener implements ListenerInterface
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
     * @var RequestParamFetcherInterface
     */
    protected $parameterFetcher;

    /**
     * @var string
     */
    protected $deliveryTokenParameter;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, RequestParamFetcherInterface $parameterFetcher, $deliveryTokenName)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->parameterFetcher = $parameterFetcher;
        $this->deliveryTokenParameter = $deliveryTokenName;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $this->parameterFetcher->fetch($request, $this->deliveryTokenParameter);

        if ($token === null) {
            return;
        }

        $apiToken = new TransportToken($token);

        try {
            $authToken = $this->authenticationManager->authenticate($apiToken);
            $this->tokenStorage->setToken($authToken);
        } catch (AuthenticationException $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $response->setContent('Authentication failed');
            $event->setResponse($response);
        }
    }

    /**
     * @param string $deliveryTokenParameter
     */
    public function setDeliveryTokenParameter($deliveryTokenParameter)
    {
        $this->deliveryTokenParameter = $deliveryTokenParameter;
    }
}