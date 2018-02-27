<?php

namespace InteruploadBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package InteruploadBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('interupload');
        $rootNode
            ->children()
            ->scalarNode('url_wsdl')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->scalarNode('iup_url_ticket')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();        
        $rootNode
            ->children()
            ->scalarNode('base_path_upload')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->booleanNode('debug')
            ->defaultValue(false)
            ->end();
        $rootNode
            ->children()
            ->scalarNode('encoding')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->scalarNode('cache_wsdl')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->scalarNode('bundle_name_entities')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->scalarNode('table_ticket_name')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode
            ->children()
            ->scalarNode('table_configuration_name')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();

        return $treeBuilder;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'interupload';
    }
}