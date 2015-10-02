<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiToken extends AbstractToken implements ApiTokenInterface
{
    public function __construct(Entity\ApiUserInterface $user)
    {
        parent::__construct($user->getRoles());

        $this->setUser($user);

        parent::setAuthenticated(true);
    }

    public function getCredentials()
    {
        return '';
    }
}