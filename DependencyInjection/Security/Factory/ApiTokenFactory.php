<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class ApiTokenFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.api_token.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('api_token.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(1, $config['lifetime'])
            ->replaceArgument(2, $config['idle_time']);

        $resolvedParameterFetcherId = 'api_token.fetcher' . $id;
        $container
            ->setDefinition($resolvedParameterFetcherId, new DefinitionDecorator('api_token.fetcher.' . $config['delivery']['type']));

        $listenerId = 'security.authentication.listener.api_token.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('api_token.security.authentication.listener'))
            ->replaceArgument(2, new Reference($resolvedParameterFetcherId))
            ->replaceArgument(3, $config['delivery']['parameter']);

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'api_token';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('delivery')
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('headers')
                            ->validate()
                                ->ifNotInArray(['headers', 'query_string', 'post_body', 'json_post_body'])
                                ->thenInvalid('Unsupported delivery type "%s"')
                             ->end()
                        ->end()
                        ->scalarNode('parameter')
                            ->defaultValue('X-Api-Token')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('lifetime')->defaultValue(null)->end()
                ->scalarNode('idle_time')->defaultValue(null)->end()
            ->end();
    }
}