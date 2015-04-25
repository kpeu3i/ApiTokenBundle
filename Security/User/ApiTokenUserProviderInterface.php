<?php

namespace Bukatov\ApiTokenBundle\Security\User;

use Bukatov\ApiTokenBundle\Entity\ApiUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ApiTokenUserProviderInterface extends UserProviderInterface
{
    /**
     * @param $apiToken
     * @return null|ApiUserInterface
     */
    public function loadUserByApiToken($apiToken);

    /**
     * @param $apiToken
     * @return mixed
     */
    public function refreshApiTokenLastUsedAt($apiToken);
}