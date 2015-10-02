<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage;

use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiTokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\Storage\ApiTokenStorageInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class SqlApiTokenStorage implements ApiTokenStorageInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(ManagerRegistry $registry, $name = null)
    {
        $this->em = $registry->getManager($name);
    }

    public function get($value)
    {
        return $this->em->getRepository('BukatovApiTokenBundle:ApiToken')->loadApiTokenByValue($value);
    }

    public function set($value, ApiTokenInterface $token)
    {
        $this->em->persist($token);
        $this->em->flush($token);
    }

    public function delete($value)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->delete('BukatovApiTokenBundle:ApiToken', 'a')
            ->where('a.value = :value');

        $qb->setParameter('value', $value);

        return $qb->getQuery()->execute();
    }
}