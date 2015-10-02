<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait ApiUserTrait
{
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Bukatov\ApiTokenBundle\Entity\ApiToken", mappedBy="user")
     */
    protected $apiTokens;

    public function getApiTokens()
    {
        return $this->apiTokens;
    }

    public function setApiTokens(ArrayCollection $apiTokens)
    {
        foreach ($apiTokens as $apiToken) {
            $this->addApiToken($apiToken);
        }

        return $this;
    }

    public function addApiToken(ApiToken $apiToken)
    {
        $apiToken->setUser($this);

        $this->getApiTokens()->add($apiToken);

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken)
    {
        $this->getApiTokens()->removeElement($apiToken);

        return $this;
    }
}