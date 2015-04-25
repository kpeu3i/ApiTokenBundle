<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ApiUserRepository extends EntityRepository implements ApiUserRepositoryInterface
{
    use ApiUserRepositoryTrait;
}