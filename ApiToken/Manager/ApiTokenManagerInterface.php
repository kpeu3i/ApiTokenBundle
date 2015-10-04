<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Manager;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;

interface ApiTokenManagerInterface
{
    public function findByToken($key);

    public function save(ApiTokenInterface $token, $ttl = 0);

    public function deleteByToken($token);
}