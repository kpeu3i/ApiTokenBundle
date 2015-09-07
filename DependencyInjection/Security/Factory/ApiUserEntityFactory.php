<?php

namespace Bukatov\ApiTokenBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApiUserEntityFactory implements UserProviderFactoryInterface
{
    private $key;
    private $providerId;

    public function __construct($key, $providerId)
    {
        $this->key = $key;
        $this->providerId = $providerId;
    }

    public function create(ContainerBuilder $container, $id, $config)
    {
        $class = isset($config['class']) ? $config['class'] : '%bukatov_api_token.provider.user_entity_class%';

        $container
            ->setDefinition($id, new DefinitionDecorator($this->providerId))
            ->replaceArgument(1, $class)
            ->replaceArgument(2, $config['manager_name']);
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('class')
                    ->defaultNull()
                ->end()
                ->scalarNode('manager_name')
                    ->defaultNull()
                ->end()
            ->end();
    }

    public function getKey()
    {
        return $this->key;
    }
}