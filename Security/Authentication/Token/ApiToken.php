<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiToken extends AbstractToken
{
    public function __construct($user, array $roles = [])
    {
        parent::__construct($roles);

        $this->setUser($user);
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}