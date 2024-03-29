<?php

namespace HBM\BasicsBundle\DependencyInjection;

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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('hbm_basics');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
          ->children()
            ->arrayNode('confirm')->addDefaultsIfNotSet()
              ->children()
                ->scalarNode('template')->defaultValue('partials/confirm.html.twig')->end()
                ->scalarNode('navi')->defaultValue('default')->end()
              ->end()
            ->end()
            ->arrayNode('form')->addDefaultsIfNotSet()
              ->children()
                ->arrayNode('submit_button_classes')->addDefaultsIfNotSet()
                  ->children()
                    ->scalarNode('default')->defaultValue('btn btn-lg btn-block')->end()
                    ->scalarNode('primary')->defaultValue('btn-primary')->end()
                    ->scalarNode('affirm')->defaultValue('btn-success')->end()
                    ->scalarNode('decline')->defaultValue('btn-danger')->end()
                  ->end()
                ->end()
              ->end()
            ->end()
          ->end();

        return $treeBuilder;
    }
}
