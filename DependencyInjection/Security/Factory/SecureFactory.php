<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class SecureFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'bukatov_api_token.security.authentication.provider.secure.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('bukatov_api_token.security.authentication.provider.secure'))
            ->replaceArgument(1, new Reference($userProvider))
            ;

        $resolvedParameterFetcherId = 'bukatov_api_token.request_param_fetcher.' . $config['delivery']['type'];
        $listenerId = 'security.authentication.listener.bukatov_api_token.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('bukatov_api_token.security.authentication.listener.secure'))
            ->replaceArgument(2, new Reference($resolvedParameterFetcherId))
            ->addMethodCall('setDeliveryTokenParameter', [$config['delivery']['token_parameter']])
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
                            ->defaultValue('headers')
                            ->validate()
                                ->ifNotInArray(['headers', 'query_string', 'post_body', 'json_post_body'])
                                ->thenInvalid('Unsupported transport type "%s"')
                             ->end()
                        ->end()
                        ->scalarNode('token_parameter')
                            ->defaultValue('X-Api-Token')
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
        return 'api_token_secure_area';
    }
}