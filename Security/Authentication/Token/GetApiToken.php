<?php

namespace Bukatov\ApiTokenBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class GetApiToken extends UsernamePasswordToken
{
    /**
     * @var string
     */
    protected $providerKey;

    /**
     * @var string
     */
    protected $ipAddress;

    public function __construct($user, $credentials, array $roles = [])
    {
        $this->providerKey = uniqid(time(), true);

        parent::__construct($user, $credentials, $this->providerKey, $roles);
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }
}