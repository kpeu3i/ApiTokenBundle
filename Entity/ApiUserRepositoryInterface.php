<?php

namespace Bukatov\ApiTokenBundle\Entity;

interface ApiUserRepositoryInterface
{
    public function loadUserByApiToken($apiToken);

    public function loadUserByUsername($username);
}