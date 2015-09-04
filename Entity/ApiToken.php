<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Bukatov\ApiTokenBundle\Entity\ApiTokenRepository")
 *
 * @UniqueEntity("token")
 */
class ApiToken
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @Assert\Length(min="40", max="40")
     */
    protected $token;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lifetime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $idleTime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastUsedAt;

    /**
     * @var ApiUserInterface
     * @ORM\OneToOne(targetEntity="ApiUserInterface", inversedBy="bukatovApiToken")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->token;
    }

    public function refresh()
    {
        $this->token = $this->generateToken($this->user->getSalt());
        $this->createdAt = new \DateTime();
        $this->lastUsedAt = null;

        return $this;
    }

    private function generateToken($secret)
    {
        return sha1(uniqid(mt_rand() . $secret . mt_rand(), true));
    }

    public function isValid($currentTimestamp = null)
    {
        $createdAtTimestamp = $this->getCreatedAt()->getTimestamp();
        $lastUsedAtTimestamp = $this->getLastUsedAt() ? $this->getLastUsedAt()->getTimestamp() : null;
        $currentTimestamp = $currentTimestamp ?: time();

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

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
     * @return $this
     */
    public function setLastUsedAt(\DateTime $lastUsedAt)
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }

    /**
     * @return $this
     */
    public function refreshLastUsedAt()
    {
        $this->setLastUsedAt(new \DateTime());

        return $this;
    }

    /**
     * @return $this
     */
    public function refreshCreatedAt()
    {
        $this->setCreatedAt(new \DateTime());

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return ApiUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param ApiUserInterface $user
     */
    public function setUser(ApiUserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @param int $idleTime
     */
    public function setIdleTime($idleTime)
    {
        $this->idleTime = $idleTime;
    }
}