<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

class SecureToken extends AbstractToken
{
    /**
     * @var ApiTokenInterface
     */
    protected $apiToken;

    public function __construct(UserInterface $user, ApiTokenInterface $apiToken)
    {
        $this->apiToken = $apiToken;

        parent::__construct($user->getRoles());

        $this->setUser($user);

        parent::setAuthenticated(true);
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function getCredentials()
    {
        return '';
    }
}