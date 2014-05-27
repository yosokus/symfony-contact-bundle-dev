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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RPSContactExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');

        // get the RPSContact configuration
        $configs = $container->getExtensionConfig($this->getAlias());
        $contactConfig = $this->processConfiguration(new Configuration(), $configs);

        // check if LiipImagineBundle is registered
        if (isset($bundles['LiipImagineBundle'])) {
            $rpsConfig = array('filter_image' => true);

            $liipImagineConfig = array();   // liip_imagine confguraion
            $contactFilterSets = array();   // RPSContaactBundle liip_imagine filter set

            // get the LiipImagine configurations
            $liipImagineConfigs = $container->getExtensionConfig('liip_imagine');   // array of liip_imagine configs

            // merge liip_imagine configs
            foreach ($liipImagineConfigs as $config) {
                $liipImagineConfig = array_merge($liipImagineConfigs, $config);
            }

            // set the RPSContactBundle liip_imagine filter_sets configuration
            // if not overridden in the app/config/config.yml file
            if (!isset($liipImagineConfig['filter_sets']['rps_contact_avatar'])) {
                $avatarConfig = $contactConfig['image']['avatar'];    // avatar image configuration
                $contactFilterSets['rps_contact_avatar'] = array(
                    'quality' => $avatarConfig['quality'],
                    'filters' => array(
                        'thumbnail' => array(
                            'mode' => 'outbound',
                            'size' => array(
                                $avatarConfig['width'],
                                $avatarConfig['height']
                            )
                        )
                    )
                );
            }

            if (!isset($liipImagineConfig['filter_sets']['rps_contact_thumb'])) {
                $thumbConfig = $contactConfig['image']['thumb'];   // thumb image configuration
                $contactFilterSets['rps_contact_thumb'] = array(
                    'quality' => $thumbConfig['quality'],
                    'filters' => array(
                        'thumbnail' => array(
                            'mode' => 'outbound',
                            'size' => array(
                                $thumbConfig['width'],
                                $thumbConfig['height']
                            )
                        )
                    )
                );
            }

            // set the liip_imagine rps_contact filters configuration
            if (!empty($contactFilterSets)) {
                $container->prependExtensionConfig(
                    'liip_imagine', array('filter_sets' => $contactFilterSets)
                );
            }

        } else {
            $rpsConfig = array('filter_image' => false);
        }

        // check if WhiteOctoberPagerfantaBundle is registered
        // if not set the default pager class
        // can be overridden by setting the rps_contact.pager.class config
        if (!isset($bundles['WhiteOctoberPagerfantaBundle'])) {
            if ( 'orm' == $contactConfig['db_driver']) {
                $rpsConfig['class']['pager'] = 'RPS\CoreBundle\Pager\DefaultORM';
            } else {
                $rpsConfig['class']['pager'] = 'RPS\CoreBundle\Pager\DefaultMongodb';
            }
        }

        // add the RPSContact configurations
        // all options can be overridden in the app/config/config.yml file
        $container->prependExtensionConfig('rps_contact', $rpsConfig);
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $loader->load('form.xml');

        // core config
        $container->setParameter('rps_contact.contact_per_page', $config['contact_per_page']);

        // image config
        $container->setParameter('rps_contact.filter_image', $config['filter_image']);
        $container->setParameter('rps_contact.avatar_quality', $config['image']['avatar']['quality']);
        $container->setParameter('rps_contact.avatar_width', $config['image']['avatar']['width']);
        $container->setParameter('rps_contact.avatar_height', $config['image']['avatar']['height']);
        $container->setParameter('rps_contact.thumb_quality', $config['image']['thumb']['quality']);
        $container->setParameter('rps_contact.thumb_width', $config['image']['thumb']['width']);
        $container->setParameter('rps_contact.thumb_height', $config['image']['thumb']['height']);

        if (isset($config['default_avatar'])) {
            $container->setParameter('rps_contact.default_avatar', $config['default_avatar']);
        }

        // forms
        $container->setParameter('rps_contact.form.contact.name', $config['form']['contact']['name']);
        $container->setParameter('rps_contact.form.contact.type', $config['form']['contact']['type']);
        $container->setParameter('rps_contact.form.contact.class', $config['form']['contact']['class']);

        // views
        $container->setParameter('rps_contact.view.list', $config['view']['list']);
        $container->setParameter('rps_contact.view.show', $config['view']['show']);
        $container->setParameter('rps_contact.view.new', $config['view']['new']);
        $container->setParameter('rps_contact.view.edit', $config['view']['edit']);

        // model
        if (isset($config['class']['model'])) {
            $container->setParameter('rps_contact.model.contact.class', $config['class']['model']);
        }

        // manager
        if (isset($config['class']['manager'])) {
            $container->setParameter('rps_contact.manager.contact.class', $config['class']['manager']);
        }

        // pager
        if (isset($config['class']['pager'])) {
            $container->setParameter('rps_contact.pager.class', $config['class']['pager']);
        }

        // load external pager if set  else load the default pager
        if (isset($config['service']['pager'])) {
            $container->setAlias('rps_contact.pager', $config['service']['pager']);
        } else {
            $container->setAlias('rps_contact.pager', 'rps_contact.pager.default');
        }
    }
}
