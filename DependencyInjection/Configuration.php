<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('bukatov_api_token')
            ->addDefaultsIfNotSet()
            ->children()

                ->arrayNode('token')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('lifetime')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('idle_time')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('transport')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('on_secure_area')
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
                                    ->defaultValue('X-Api-Token')
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('on_login_area')
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
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
