<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BukatovApiTokenExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bukatov_api_token.lifetime.absolute', $config['lifetime']['absolute']);
        $container->setParameter('bukatov_api_token.lifetime.inactive', $config['lifetime']['inactive']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $managerDefinition = $container->getDefinition('bukatov_api_token.manager');
        $managerDefinition->replaceArgument(0, new Reference('bukatov_api_token.storage.' . $config['storage']));
    }
}
