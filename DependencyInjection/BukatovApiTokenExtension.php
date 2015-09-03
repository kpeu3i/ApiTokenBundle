<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
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

        $container->setParameter('bukatov_api_token.token.lifetime', $config['token']['lifetime']);
        $container->setParameter('bukatov_api_token.token.idle_time', $config['token']['idle_time']);

        $container->setParameter('bukatov_api_token.transport.on_login_area.type', $config['transport']['on_login_area']['type']);
        $container->setParameter('bukatov_api_token.transport.on_login_area.username_parameter', $config['transport']['on_login_area']['username_parameter']);
        $container->setParameter('bukatov_api_token.transport.on_login_area.password_parameter', $config['transport']['on_login_area']['password_parameter']);

        $container->setParameter('bukatov_api_token.transport.on_secure_area.type', $config['transport']['on_secure_area']['type']);
        $container->setParameter('bukatov_api_token.transport.on_secure_area.parameter', $config['transport']['on_secure_area']['parameter']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
