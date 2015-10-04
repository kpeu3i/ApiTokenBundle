<?php

namespace Bukatov\ApiTokenBundle\Security\Core\User;

use Bukatov\ApiTokenBundle\Entity\ApiToken;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

interface ApiUserInterface extends UserInterface
{
    public function getApiTokens();

    public function setApiTokens(ArrayCollection $apiTokens);

    public function addApiToken(ApiToken $apiToken);

    public function removeApiToken(ApiToken $apiToken);
}