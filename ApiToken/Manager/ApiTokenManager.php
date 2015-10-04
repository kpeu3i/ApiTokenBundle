<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Manager;

use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Bukatov\ApiTokenBundle\ApiToken\Storage\ApiTokenStorageInterface;

class ApiTokenManager implements ApiTokenManagerInterface
{
    /**
     * @var ApiTokenStorageInterface
     */
    protected $storage;

    public function __construct(ApiTokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function findByToken($token)
    {
        return $this->storage->get($token);
    }

    public function deleteByToken($token)
    {
        return $this->storage->delete($token);
    }

    public function save(ApiTokenInterface $token, $ttl = 0)
    {
        return $this->storage->set($token, $ttl);
    }
}