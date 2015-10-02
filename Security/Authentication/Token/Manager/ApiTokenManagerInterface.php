<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token\Manager;

use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiTokenInterface;

interface ApiTokenManagerInterface
{
    public function find($value);

    public function save($value, ApiTokenInterface $token);

    public function delete($value);
}