<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ApiTokenRepository extends EntityRepository
{
    public function createOrUpdateApiToken(ApiUserInterface $user)
    {
        $apiToken = $user->getApiToken();

        if (!$apiToken instanceof ApiToken) {
            $apiToken = new ApiToken();
            $apiToken->setUser($user);
        }

        $apiToken->refresh();

        return $apiToken;
    }
}