<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage;

use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiTokenInterface;

interface ApiTokenStorageInterface
{
    public function get($value);

    public function set($value, ApiTokenInterface $token);

    public function delete($value);
}