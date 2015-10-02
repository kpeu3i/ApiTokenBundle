<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ApiTokenRepository extends EntityRepository
{
    public function loadApiTokenByValue($token)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('t, u')
            ->from('BukatovApiTokenBundle:ApiToken', 't')
            ->innerJoin('t.user', 'u')
            ->where('t.token = :token');

        $qb->setParameter('token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }

//    public function createOrUpdateApiToken(ApiUserInterface $user)
//    {
//        $apiToken = $user->getApiToken();
//
//        if (!$apiToken instanceof ApiToken) {
//            $apiToken = new ApiToken();
//            $apiToken->setUser($user);
//            $user->setApiToken($apiToken);
//        }
//
//        $apiToken->refresh();
//
//        return $apiToken;
//    }
}