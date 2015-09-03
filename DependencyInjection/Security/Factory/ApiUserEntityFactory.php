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
        $container
            ->setDefinition($id, new DefinitionDecorator($this->providerId))
            ->replaceArgument(1, $config['class'])
            ->replaceArgument(2, $config['manager_name']);
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('class')
                    ->isRequired()
                    ->cannotBeEmpty()
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