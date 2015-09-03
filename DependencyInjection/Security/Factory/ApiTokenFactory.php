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
        $transportParameter = isset($config['transport']['parameter']) ? $config['transport']['parameter'] : '%bukatov_api_token.transport.on_secure_area.parameter%';

        $providerId = 'bukatov_api_token.security.authentication.token.provider.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('bukatov_api_token.security.authentication.token.provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $resolvedParameterFetcherId = 'bukatov_api_token.fetcher.' . $id;
        $container
            ->setDefinition($resolvedParameterFetcherId, new DefinitionDecorator('bukatov_api_token.fetcher.' . $config['transport']['type']));

        $listenerId = 'security.authentication.listener.bukatov_api_token.' . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('bukatov_api_token.security.authentication.listener'))
            ->replaceArgument(2, new Reference($resolvedParameterFetcherId))
            ->replaceArgument(3, $transportParameter);

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
                            ->defaultValue('headers')
                            ->validate()
                                ->ifNotInArray(['headers', 'query_string', 'post_body', 'json_post_body'])
                                ->thenInvalid('Unsupported transport type "%s"')
                             ->end()
                        ->end()
                        ->scalarNode('parameter')
                            ->defaultNull()
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