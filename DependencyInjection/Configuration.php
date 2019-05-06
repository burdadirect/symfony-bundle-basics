<?php

namespace HBM\BasicsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder() {
    $treeBuilder = new TreeBuilder('hbm_basics');

    if (method_exists($treeBuilder, 'getRootNode')) {
      $rootNode = $treeBuilder->getRootNode();
    } else {
      $rootNode = $treeBuilder->root('hbm_basics');
    }

    $rootNode
      ->children()
        ->arrayNode('confirm')->addDefaultsIfNotSet()
          ->children()
            ->scalarNode('template')->defaultValue('partials/confirm.html.twig')->end()
            ->scalarNode('navi')->defaultValue('default')->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }

}
