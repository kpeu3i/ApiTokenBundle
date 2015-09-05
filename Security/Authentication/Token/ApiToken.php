<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiToken extends AbstractToken
{
    public function __construct($user, array $roles = [])
    {
        $this->setUser($user);

        parent::__construct($roles);
    }

    public function getCredentials()
    {
        return '';
    }
}