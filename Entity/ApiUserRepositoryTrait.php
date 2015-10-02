<?php

namespace Bukatov\ApiTokenBundle\Entity;

trait ApiUserRepositoryTrait
{
    public function loadUserByUsername($username)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.username = :username');

        $qb->setParameter('username', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }
}