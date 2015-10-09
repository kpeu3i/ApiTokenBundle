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
                ->arrayNode('lifetime')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('absolute')
                            ->defaultValue(0)
                            ->min(0)
                        ->end()
                        ->integerNode('inactive')
                            ->defaultValue(7200)
                            ->min(0)
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('storage')
                    ->defaultValue('sql')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
