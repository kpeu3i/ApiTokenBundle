<?php

namespace Bukatov\ApiTokenBundle\Security\Core\User;

use Doctrine\Common\Persistence\ManagerRegistry;

class UserProviderFactory
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var null|string
     */
    protected $managerName;

    public function __construct(ManagerRegistry $registry, $managerName = null)
    {
        $this->registry = $registry;
        $this->managerName = $managerName;
    }

    public function createEntityApiTokenUserProvider($userClass)
    {
        return new EntityApiTokenUserProvider($this->registry, $userClass, $this->managerName);
    }
}