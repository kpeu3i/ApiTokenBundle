<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class LoginFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'bukatov_api_token.security.authentication.provider.firewall.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('bukatov_api_token.security.authentication.provider.login'))
            ->replaceArgument(1, new Reference($userProvider))
            ;

        $resolvedParameterFetcherId = 'bukatov_api_token.request_param_fetcher.' . $config['delivery']['type'];
        $listenerId = 'bukatov_api_token.security.authentication.listener.firewall.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('bukatov_api_token.security.authentication.listener.login'))
            ->replaceArgument(2, $id)
            ->replaceArgument(3, new Reference($resolvedParameterFetcherId))
            ->addMethodCall('setDeliveryUsernameParameter', [$config['delivery']['username_parameter']])
            ->addMethodCall('setDeliveryPasswordParameter', [$config['delivery']['password_parameter']])
            ;

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('delivery')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('json_post_body')
                            ->validate()
                                ->ifNotInArray(['headers', 'query_string', 'post_body', 'json_post_body'])
                                ->thenInvalid('Unsupported transport type "%s"')
                             ->end()
                        ->end()
                        ->scalarNode('username_parameter')
                            ->defaultValue('username')
                        ->end()
                        ->scalarNode('password_parameter')
                            ->defaultValue('password')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'api_token_login_area';
    }
}