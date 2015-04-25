<?php

namespace Bukatov\ApiTokenBundle\Security\User;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Bukatov\ApiTokenBundle\Entity\ApiUserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityApiTokenUserProvider implements ApiTokenUserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    /**
     * @var ApiUserRepositoryInterface
     */
    private $useRepository;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    private $metadata;

    public function __construct(ManagerRegistry $registry, $class, $managerName = null)
    {
        $this->em = $registry->getManager($managerName);

        $this->class = $class;
        $this->metadata = $this->em->getClassMetadata($class);

        if (false !== strpos($this->class, ':')) {
            $this->class = $this->metadata->getName();
        }

        $this->useRepository = $this->em->getRepository($class);

        if (!$this->useRepository instanceof ApiUserRepositoryInterface) {
            throw new \InvalidArgumentException(sprintf('Repository class %s must implement ApiUserRepositoryInterface', $this->class));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByApiToken($apiToken)
    {
        $user = $this->useRepository->loadUserByApiToken($apiToken);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User with api token "%s" not found.', $apiToken));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshApiTokenLastUsedAt($apiToken)
    {
        return $this->useRepository->refreshApiTokenLastUsedAt($apiToken);
    }

    public function loadUserByUsername($username)
    {
        throw new UnsupportedUserException();
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // This is used for storing authentication in the session
        // but the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless

        throw new UnsupportedUserException();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }
}