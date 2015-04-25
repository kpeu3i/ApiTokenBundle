<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\EntityRepository;

trait ApiUserRepositoryTrait
{
    public function loadUserByApiToken($apiToken)
    {
        $this->validateMethodCallContext();

        /* @var EntityRepository $this */

        $qb = $this->createQueryBuilder('u');
        $qb->innerJoin('u.apiToken', 't', 'WITH', 't.token = :api_token');

        $qb->setParameter('api_token', $apiToken);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function refreshApiTokenLastUsedAt($apiToken)
    {
        $this->validateMethodCallContext();

        /* @var EntityRepository $this */

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->update('BukatovApiTokenBundle:ApiToken', 't')
            ->set('t.lastUsedAt', ':date')
            ->where('t.token = :api_token');

        $qb
            ->setParameter('date', new \DateTime())
            ->setParameter('api_token', $apiToken);

        return $qb->getQuery()->execute();
    }

    private function validateMethodCallContext()
    {
        if (!$this instanceof EntityRepository) {
            throw new \LogicException('ApiUserRepositoryTrait can be used only in Doctrine\ORM\EntityRepository context');
        }
    }
}