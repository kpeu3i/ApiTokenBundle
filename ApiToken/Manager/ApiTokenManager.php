<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Manager;

use Bukatov\ApiTokenBundle\ApiToken\ApiToken;
use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Bukatov\ApiTokenBundle\ApiToken\Storage\ApiTokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiTokenManager implements ApiTokenManagerInterface
{
    /**
     * @var ApiTokenStorageInterface
     */
    protected $storage;

    public function __construct(ApiTokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function create(UserInterface $user, $ipAddress, $absoluteLifetime = 0)
    {
        $apiToken = new ApiToken();
        $apiToken->setToken($this->generateToken($user->getSalt()));
        $apiToken->setUsername($user->getUsername());
        $apiToken->setIpAddress($ipAddress);

        $absoluteLifetime = intval($absoluteLifetime);
        if ($absoluteLifetime > 0) {
            $expiresAt = new \DateTime();
            $expiresAt->add(\DateInterval::createFromDateString(sprintf('%s seconds', $absoluteLifetime)));
            $apiToken->setExpiresAt($expiresAt);
        }

        return $apiToken;
    }

    protected function generateToken($secret)
    {
        return sha1(uniqid(mt_rand() . $secret . mt_rand(), true)) . sha1(uniqid(mt_rand() . $secret . mt_rand(), true));
    }

    public function find($key)
    {
        return $this->storage->get($key);
    }

    public function save($key, ApiTokenInterface $token, $inactiveLifetime = 0)
    {
        return $this->storage->set($key, $token, $inactiveLifetime);
    }

    public function delete($key)
    {
        return $this->storage->delete($key);
    }

    public function isValid(ApiTokenInterface $apiToken)
    {
        if ($expiresAt = $apiToken->getExpiresAt()) {
            $currentDateTime = new \DateTime();
            if ($currentDateTime >= $expiresAt) {
                return false;
            }
        }

        return true;
    }
}