<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Storage;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;

class ApcApiTokenStorage implements ApiTokenStorageInterface
{
    public function get($key)
    {
        $result = apc_fetch($key);

        $apiToken = $result ? unserialize($result) : null;
        $apiToken = $apiToken instanceof ApiTokenInterface ? $apiToken : null;

        return $apiToken;
    }

    public function set($key, ApiTokenInterface $token, $lifetime = 0)
    {
        apc_store($token->getToken(), serialize($token), $lifetime);
    }

    public function delete($token)
    {
        apc_delete($token);
    }
}