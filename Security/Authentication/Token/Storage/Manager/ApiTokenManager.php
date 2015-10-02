<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage\Manager;

use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiTokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\Manager\ApiTokenManagerInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage\ApiTokenStorageInterface;

class ApiTokenManager implements \Bukatov\ApiTokenBundle\Security\Authentication\Token\Manager\ApiTokenManagerInterface
{
    /**
     * @var \Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage\ApiTokenStorageInterface
     */
    protected $storage;

    public function __construct(ApiTokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function find($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('The value must be a string');
        }

        return $this->storage->get($value);
    }

    public function save($value, ApiTokenInterface $token)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('The value must be a string');
        }

        return $this->storage->set($value, $token);
    }

    public function delete($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('The value must be a string');
        }

        return $this->storage->delete($value);
    }
}