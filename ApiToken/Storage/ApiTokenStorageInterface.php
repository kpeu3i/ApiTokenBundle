<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Storage;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;

interface ApiTokenStorageInterface
{
    /**
     * @param $token
     *
     * @return null|ApiTokenInterface
     */
    public function get($token);

    public function set(ApiTokenInterface $token, $ttl = 0);

    public function delete($token);
}