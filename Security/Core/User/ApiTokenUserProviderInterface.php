<?php

namespace Bukatov\ApiTokenBundle\Security\Core\User;

use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ApiTokenUserProviderInterface extends UserProviderInterface
{
    /**
     * @param $token
     *
     * @return null|Entity\ApiToken
     */
    public function loadApiTokenByValue($token);
}