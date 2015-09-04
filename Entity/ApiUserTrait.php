<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ApiUserTrait
{
    /**
     * @var ApiToken
     * @ORM\OneToOne(targetEntity="Bukatov\ApiTokenBundle\Entity\ApiToken", mappedBy="user")
     */
    protected $bukatovApiToken;

    public function getApiToken()
    {
        return $this->bukatovApiToken;
    }

    public function setApiToken(ApiToken $apiToken)
    {
        $this->bukatovApiToken = $apiToken;

        return $this;
    }
}