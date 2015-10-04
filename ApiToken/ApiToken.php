<?php

namespace Bukatov\ApiTokenBundle\ApiToken;

class ApiToken implements ApiTokenInterface
{
    protected $token;
    protected $username;
    protected $createdAt;
    protected $lastUsedAt;
    protected $ipAddress;

    protected $initialized = false;
    protected $locked = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
        $this->set('username',  $username);

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

    public function isInitialized()
    {
        return $this->initialized;
    }

    protected function set($property, $value)
    {
        if ($this->locked) {
            throw new \RuntimeException('ApiToken is already locked');
        }

        $this->{$property} = $value;
    }

    public function init()
    {
        if ($this->initialized) {
            throw new \RuntimeException('ApiToken is already initialized');
        }

        $this->initialized = true;
        $this->locked = true;
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->getSupportedProperties() as $property) {
            $data[$property] = $this->{$property};
        }

        return $data;
    }

    public static function fromArray(array $data)
    {
        $apiToken = new self();
        foreach ($apiToken->getSupportedProperties() as $property) {
            $data[$property] = $apiToken->{$property};
        }

        return $apiToken;
    }

    protected function getSupportedProperties()
    {
        return ['token', 'username', 'createdAt', 'lastUsedAt', 'ipAddress'];
    }
}