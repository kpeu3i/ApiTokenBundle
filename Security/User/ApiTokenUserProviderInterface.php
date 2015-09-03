<?php

namespace Bukatov\ApiTokenBundle\Security\User;

use Bukatov\ApiTokenBundle\Entity;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ApiTokenUserProviderInterface extends UserProviderInterface
{
    /**
     * @param $token
     *
     * @return Entity\ApiUserInterface|null
     */
    public function loadUserByApiToken($token);

    /**
     * @param Entity\ApiUserInterface $user
     *
     * @param $lifetime
     * @param $idleTime
     *
     * @return Entity\ApiToken
     */
    public function createOrUpdateApiTokenForUser(Entity\ApiUserInterface $user, $lifetime = null, $idleTime = null);

    /**
     * @param Entity\ApiUserInterface $user
     *
     * @return Entity\ApiToken
     */
    public function refreshApiTokenLastUsedAtForUser(Entity\ApiUserInterface $user);
}