<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Provider;

use Bukatov\ApiTokenBundle\Security\User\ApiTokenUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bukatov\ApiTokenBundle\Security\Authentication\Token\ApiToken;
use Bukatov\ApiTokenBundle\Entity;

class ApiTokenProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $lifetime;
    private $idleTime;

    public function __construct(ApiTokenUserProviderInterface $userProvider, $lifetime, $idleTime)
    {
        $this->userProvider = $userProvider;
        $this->lifetime = $lifetime ? intval($lifetime) : null;
        $this->idleTime = $idleTime ? intval($idleTime) : null;
    }

    public function authenticate(TokenInterface $token)
    {
        $apiTokenHash = $token->getUser();
        $user = $this->userProvider->loadUserByApiToken($apiTokenHash);

        if ($user && $this->validateApiToken($user->getApiToken())) {
            $authenticatedToken = new ApiToken($user->getRoles());
            $authenticatedToken->setUser($user);

            $this->userProvider->refreshApiTokenLastUsedAt($apiTokenHash);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The API-Token authentication failed');
    }

    protected function validateApiToken(Entity\ApiToken $apiToken)
    {
        $createdAtTimestamp = $apiToken->getCreatedAt()->getTimestamp();
        $lastUsedAtTimestamp = $apiToken->getLastUsedAt() ? $apiToken->getLastUsedAt()->getTimestamp() : null;
        $currentTimestamp = time();

        if ($this->lifetime !== null) {
            // Check created time is not in the future
            if ($createdAtTimestamp > $currentTimestamp) {
                return false;
            }

            // Expire token after lifetime
            if ($currentTimestamp - $createdAtTimestamp > $this->lifetime) {
                return false;
            }
        }

        if ($this->idleTime !== null) {
            // Check last used time is not in the future
            if ($lastUsedAtTimestamp > $currentTimestamp) {
                return false;
            }

            // Expire token after idle time
            if ($lastUsedAtTimestamp !== null && $currentTimestamp - $lastUsedAtTimestamp > $this->idleTime) {
                return false;
            }
        }

        return true;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiToken;
    }
}