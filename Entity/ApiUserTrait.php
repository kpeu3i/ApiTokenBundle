<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ApiUserTrait
{
    /**
     * @var ApiToken
     * @ORM\OneToOne(targetEntity="Bukatov\ApiTokenBundle\Entity\ApiToken", mappedBy="user")
     */
    protected $apiToken;

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function setApiToken(ApiToken $apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}