<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Manager;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface ApiTokenManagerInterface
{
    /**
     * @param UserInterface $user
     * @param string $ipAddress
     * @param int $absoluteLifetime
     *
     * @return ApiTokenInterface
     */
    public function create(UserInterface $user, $ipAddress, $absoluteLifetime = 0);

    public function find($key);

    public function save($key, ApiTokenInterface $token, $inactiveLifetime = 0);

    public function delete($key);

    public function isValid(ApiTokenInterface $apiToken);
}