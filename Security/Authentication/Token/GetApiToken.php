<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class GetApiToken extends UsernamePasswordToken
{
    protected $providerKey;

    public function __construct($user, $credentials, array $roles = [])
    {
        $this->providerKey = uniqid(time(), true);

        parent::__construct($user, $credentials, $this->providerKey, $roles);
    }
}