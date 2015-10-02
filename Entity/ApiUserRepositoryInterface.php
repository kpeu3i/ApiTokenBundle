<?php

namespace Bukatov\ApiTokenBundle\Entity;

interface ApiUserRepositoryInterface
{
    public function loadUserByUsername($username);
}