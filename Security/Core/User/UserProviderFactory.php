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
     * @var string
     */
    protected $userEntityClass;

    /**
     * @var null|string
     */
    protected $managerName;

    public function __construct(ManagerRegistry $registry, $userEntityClass, $managerName = null)
    {
        $this->userEntityClass = $userEntityClass;
        $this->registry = $registry;
        $this->managerName = $managerName;
    }

    public function createEntityApiTokenUserProvider()
    {
        return new ApiTokenUserProvider($this->registry, $this->userEntityClass, $this->managerName);
    }
}