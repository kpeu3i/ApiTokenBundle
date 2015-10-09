<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Storage;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;

interface ApiTokenStorageInterface
{
    /**
     * @param $key
     *
     * @return null|ApiTokenInterface
     */
    public function get($key);

    public function set($key, ApiTokenInterface $token, $lifetime = 0);

    public function delete($key);
}