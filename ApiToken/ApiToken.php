<?php

namespace Bukatov\ApiTokenBundle\ApiToken;

class ApiToken implements ApiTokenInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $lastUsedAt;

    /**
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $ipAddress;

    public function __construct()
    {
        $now = new \DateTime();

        $this->createdAt = $now;
        $this->lastUsedAt = clone $now;
    }

    public function __toString()
    {
        return (string)$this->token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->set('token', $token);

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->set('username', $username);

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->set('createdAt', $createdAt);

        return $this;
    }

    /**
     * @return null|\DateTime
     */
    public function getLastUsedAt()
    {
        return $this->lastUsedAt;
    }

    /**
     * @param \DateTime $lastUsedAt
     *
     * @return $this
     */
    public function setLastUsedAt(\DateTime $lastUsedAt)
    {
        $this->set('lastUsedAt', $lastUsedAt);

        return $this;
    }

    /**
     * @return $this
     */
    public function refreshLastUsedAt()
    {
        $this->set('lastUsedAt', new \DateTime());

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     *
     * @return \DateTime
     */
    public function setExpiresAt(\DateTime $expiresAt)
    {
        $this->set('expiresAt', $expiresAt);

        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param $ipAddress
     *
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->set('ipAddress', $ipAddress);

        return $this;
    }

    protected function set($property, $value)
    {
        $this->{$property} = $value;
    }
}