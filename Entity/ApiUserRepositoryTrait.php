<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\EntityRepository;

trait ApiUserRepositoryTrait
{
    public function loadUserByUsername($username)
    {
        $this->validateMethodCallContext();

        $qb = $this->createQueryBuilder('u');

        $qb->where('u.username = :username');

        $qb->setParameter('username', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function loadUserByApiToken($token)
    {
        $token = $token instanceof ApiToken ? $token->getToken() : $token;

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('t, u')
            ->from('BukatovApiTokenBundle:ApiToken', 't')
            ->innerJoin('t.user', 'u')
            ->where('t.token = :token');

        $qb->setParameter('token', $token);

        $apiToken = $qb->getQuery()->getOneOrNullResult();

        return $apiToken ? $apiToken->getUser() : null;
    }

    private function validateMethodCallContext()
    {
        if (!$this instanceof EntityRepository) {
            throw new \LogicException('ApiUserRepositoryTrait can be used only in Doctrine\ORM\EntityRepository context');
        }
    }
}