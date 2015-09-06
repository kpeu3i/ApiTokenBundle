<?php

namespace Bukatov\ApiTokenBundle\Security\Core\User;

use Bukatov\ApiTokenBundle\Entity;
use Bukatov\ApiTokenBundle\Entity\ApiUserInterface;
use Bukatov\ApiTokenBundle\Entity\ApiUserRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityApiTokenUserProvider implements ApiTokenUserProviderInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    /**
     * @var ApiUserRepositoryInterface|null
     */
    private $userRepository;

    public function __construct(ManagerRegistry $registry, $class, $managerName = null)
    {
        $this->em = $registry->getManager($managerName);
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByApiToken($token)
    {
        $user = $this->getUserRepository()->loadUserByApiToken($token);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User with token "%s" not found.', $token));
        }

        return $user;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->getUserRepository()->loadUserByUsername($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User with username "%s" not found.', $username));
        }

        return $user;
    }

    /**
     * @param ApiUserInterface $user
     *
     * @param null $lifetime
     * @param null $idleTime
     *
     * @return Entity\ApiToken
     */
    public function createOrUpdateApiTokenForUser(Entity\ApiUserInterface $user, $lifetime = null, $idleTime = null)
    {
        /* @var Entity\ApiToken $apiToken */
        $apiToken = $this->em->getRepository('BukatovApiTokenBundle:ApiToken')->createOrUpdateApiToken($user);

        $apiToken->setLifetime($lifetime);
        $apiToken->setIdleTime($idleTime);

        if (!$apiToken->getId()) {
            $this->em->persist($apiToken);
        }

        $this->em->flush($apiToken);

        return $apiToken;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshApiTokenLastUsedAtForUser(Entity\ApiUserInterface $user)
    {
        if ($apiToken = $user->getApiToken()) {
            $apiToken->refreshLastUsedAt();

            $this->em->flush($apiToken);
        }

        return $apiToken;
    }

    /**
     * @return ApiUserRepositoryInterface
     */
    private function getUserRepository()
    {
        if ($this->userRepository === null) {
            $metadata = $this->em->getClassMetadata($this->class);

            $class = $this->class;
            if (false !== strpos($this->class, ':')) {
                $class = $metadata->getName();
            }

            $this->userRepository = $this->em->getRepository($class);

            if (!$this->userRepository instanceof ApiUserRepositoryInterface) {
                throw new \InvalidArgumentException(sprintf('The Doctrine repository "%s" must implement ApiUserRepositoryInterface.', get_class($this->userRepository)));
            }
        }

        return $this->userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // This is used for storing authentication in the session
        // but the token is sent in each request,
        // so authentication can be stateless.
        // Throwing this exception is proper to make things stateless

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