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
        $providerId = 'security.authentication.provider.dao.' . $id;

        $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.dao'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(2, $id);

        $resolvedParameterFetcherId = 'api_token.fetcher' . $id;
        $container
            ->setDefinition($resolvedParameterFetcherId, new DefinitionDecorator('api_token.fetcher.' . $config['delivery']['type']));

        $listenerId = 'security.authentication.listener.get_api_token.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('get_api_token.security.authentication.listener'))
            ->replaceArgument(2, $id)
            ->replaceArgument(3, new Reference($resolvedParameterFetcherId))
            ->replaceArgument(4, $config['delivery']['username_parameter'])
            ->replaceArgument(5, $config['delivery']['password_parameter']);

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'get_api_token';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('delivery')
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('json_post_body')
                            ->validate()
                                ->ifNotInArray(['headers', 'query_string', 'post_body', 'json_post_body'])
                                ->thenInvalid('Unsupported delivery type "%s"')
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
}