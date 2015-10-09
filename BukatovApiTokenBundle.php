<?php

namespace Bukatov\ApiTokenBundle;

use Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory\SecureFactory;
use Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory\LoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BukatovApiTokenBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new SecureFactory());
        $extension->addSecurityListenerFactory(new LoginFactory());
    }
}
