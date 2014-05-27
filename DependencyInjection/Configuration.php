<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
   public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('rps_contact')
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()
                ->integerNode('contact_per_page')->min(1)->defaultValue(25)->end()
                ->booleanNode('filter_image')->defaultTrue()->end()
                ->scalarNode('default_avatar')->end()

                ->arrayNode('service')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('pager')->end()
                    ->end()
                ->end()

                ->arrayNode('class')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('pager')->end()
                        ->scalarNode('model')->cannotBeEmpty()->end()
                        ->scalarNode('manager')->cannotBeEmpty()->end()
                    ->end()
                ->end()

                ->arrayNode('view')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('list')
                            ->cannotBeEmpty()
                            ->defaultValue('RPSContactBundle:Default:index.html.twig')
                        ->end()
                        ->scalarNode('show')
                            ->cannotBeEmpty()
                            ->defaultValue('RPSContactBundle:Default:show.html.twig')
                        ->end()
                        ->scalarNode('new')
                            ->cannotBeEmpty()
                            ->defaultValue('RPSContactBundle:Default:new.html.twig')
                        ->end()
                        ->scalarNode('edit')
                            ->cannotBeEmpty()
                            ->defaultValue('RPSContactBundle:Default:edit.html.twig')
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('contact')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')
                                    ->cannotBeEmpty()
                                    ->defaultValue('rps_contact_contact')
                                ->end()
                                ->scalarNode('type')
                                    ->cannotBeEmpty()
                                    ->defaultValue('rps_contact_contact')
                                ->end()
                                ->scalarNode('class')
                                    ->cannotBeEmpty()
                                    ->defaultValue('RPS\ContactBundle\Form\Type\ContactType')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('image')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('avatar')->addDefaultsIfNotSet()
                            ->children()
                                ->integerNode('quality')
                                    ->cannotBeEmpty()
                                    ->defaultValue(100)
                                 ->end()
                                ->integerNode('width')
                                    ->cannotbeEmpty()
                                    ->defaultValue(150)
                                ->end()
                                ->integerNode('height')
                                    ->cannotbeEmpty()
                                    ->defaultValue(150)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('thumb')->addDefaultsIfNotSet()
                            ->children()
                                ->integerNode('quality')
                                    ->cannotbeEmpty()
                                    ->defaultValue(100)
                                ->end()
                                ->integerNode('width')
                                    ->cannotbeEmpty()
                                    ->defaultValue(50)
                                ->end()
                                ->integerNode('height')
                                    ->cannotbeEmpty()
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
