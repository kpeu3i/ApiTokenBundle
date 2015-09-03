<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class GetApiTokenFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $tokenLifetime = isset($config['lifetime']) ? $config['lifetime'] : '%bukatov_api_token.token.lifetime%';
        $tokenIdleTime = isset($config['idle_time']) ? $config['idle_time'] : '%bukatov_api_token.token.idle_time%';

        $transportUsernameParameter = isset($config['transport']['username_parameter']) ? $config['transport']['username_parameter'] : '%bukatov_api_token.transport.on_login_area.username_parameter%';
        $transportPasswordParameter = isset($config['transport']['password_parameter']) ? $config['transport']['password_parameter'] : '%bukatov_api_token.transport.on_login_area.password_parameter%';

        $providerId = 'bukatov_api_token.security.authentication.get.provider.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('bukatov_api_token.security.authentication.get.provider'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(2, $id)
            ->addMethodCall('setLifetime', [$tokenLifetime])
            ->addMethodCall('setIdleTime', [$tokenIdleTime]);

        $resolvedParameterFetcherId = 'bukatov_api_token.fetcher' . $id;
        $container
            ->setDefinition($resolvedParameterFetcherId, new DefinitionDecorator('bukatov_api_token.fetcher.' . $config['transport']['type']));

        $listenerId = 'bukatov_api_token.security.authentication.get.listener.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('bukatov_api_token.security.authentication.get.listener'))
            ->replaceArgument(2, $id)
            ->replaceArgument(3, new Reference($resolvedParameterFetcherId))
            ->replaceArgument(4, $transportUsernameParameter)
            ->replaceArgument(5, $transportPasswordParameter);

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('transport')
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
                            ->defaultNull()
                        ->end()
                        ->scalarNode('password_parameter')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('lifetime')
                    ->defaultNull()
                ->end()
                ->scalarNode('idle_time')
                    ->defaultNull()
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