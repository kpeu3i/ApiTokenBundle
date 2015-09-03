<?php

namespace Bukatov\ApiTokenBundle;

use Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory\ApiTokenFactory;
use Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory\ApiUserEntityFactory;
use Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory\GetApiTokenFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BukatovApiTokenBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ApiTokenFactory());
        $extension->addSecurityListenerFactory(new GetApiTokenFactory());

        $extension->addUserProviderFactory(new ApiUserEntityFactory('api_token_user_entity', 'bukatov_api_token.doctrine.orm.security.provider'));
    }
}
