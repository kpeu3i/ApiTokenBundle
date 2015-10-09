<?php

namespace Bukatov\ApiTokenBundle\ApiToken;

interface ApiTokenInterface
{
    /**
     * @return string
     */
    public function getToken();

    /**
     * @param $token
     *
     * @return $this
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param $username
     *
     * @return $this
     */
    public function setUsername($username);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     *
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * @return null|\DateTime
     */
    public function getLastUsedAt();

    /**
     * @param \DateTime $lastUsedAt
     *
     * @return $this
     */
    public function setLastUsedAt(\DateTime $lastUsedAt);

    /**
     * @return $this
     */
    public function refreshLastUsedAt();

    /**
     * @return \DateTime
     */
    public function getExpiresAt();

    /**
     * @param \DateTime $expiresAt
     *
     * @return \DateTime
     */
    public function setExpiresAt(\DateTime $expiresAt);

    /**
     * @return string
     */
    public function getIpAddress();

    /**
     * @param $ipAddress
     *
     * @return $this
     */
    public function setIpAddress($ipAddress);
}