<?php

namespace Bukatov\ApiTokenBundle\Security\Firewall;

use Bukatov\ApiTokenBundle\RequestParamFetcher\RequestParamFetcherInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\LoginToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class LoginListener implements ListenerInterface
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
     * @var
     */
    protected $deliveryUsernameParameter;

    /**
     * @var
     */
    protected $deliveryPasswordParameter;

    /**
     * @var
     */
    protected $providerKey;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        $providerKey,
        RequestParamFetcherInterface $parameterFetcher,
        $deliveryUsernameParameter,
        $deliveryPasswordParameter
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey = $providerKey;
        $this->parameterFetcher = $parameterFetcher;
        $this->deliveryUsernameParameter = $deliveryUsernameParameter;
        $this->deliveryPasswordParameter = $deliveryPasswordParameter;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $ipAddress = $request->getClientIp();
        $username = $this->parameterFetcher->fetch($request, $this->deliveryUsernameParameter);
        $password = $this->parameterFetcher->fetch($request, $this->deliveryPasswordParameter);

        if ($username === null) {
            return;
        }

        $token = new LoginToken($username, $password);
        $token->setIpAddress($ipAddress);

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

    /**
     * @param string $deliveryUsernameParameter
     */
    public function setDeliveryUsernameParameter($deliveryUsernameParameter)
    {
        $this->deliveryUsernameParameter = $deliveryUsernameParameter;
    }

    /**
     * @param string $deliveryPasswordParameter
     */
    public function setDeliveryPasswordParameter($deliveryPasswordParameter)
    {
        $this->deliveryPasswordParameter = $deliveryPasswordParameter;
    }
}