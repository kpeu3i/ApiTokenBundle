<?php

namespace Bukatov\ApiTokenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

interface ApiUserInterface extends UserInterface
{
    /**
     * @return null|ApiToken
     */
    public function getApiToken();

    /**
     * @param ApiToken $apiToken
     *
     * @return $this
     */
    public function setApiToken(ApiToken $apiToken);
}
