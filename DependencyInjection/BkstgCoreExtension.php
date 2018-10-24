<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BkstgCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @param array            $configs   The configuration array.
     * @param ContainerBuilder $container The container.
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // If the search bundle is active register search services.
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['BkstgSearchBundle'])) {
            $loader->load('services.search.yml');
        }
        $this->configureFilesystemAdapter($container, $config);
        $this->configureCdnAdapter($container, $config);
    }

    /**
     * Configure the filesystem adapter.
     *
     * @param ContainerBuilder $container The container builder.
     * @param array            $config    The configuration.
     *
     * @return void
     */
    public function configureFilesystemAdapter(ContainerBuilder $container, array $config): void
    {
        // add the default configuration for the S3 filesystem
        if ($container->hasDefinition('bkstg.core.adapter.filesystem.do_spaces') && isset($config['filesystem'])) {
            $container->getDefinition('bkstg.core.adapter.filesystem.do_spaces')
                ->replaceArgument(0, new Reference('bkstg.core.adapter.service.do_spaces'))
                ->replaceArgument(1, $config['filesystem']['bucket'])
                ->replaceArgument(
                    2,
                    [
                        'create' => $config['filesystem']['create'],
                        'region' => $config['filesystem']['region'],
                        'directory' => $config['filesystem']['directory'],
                        'ACL' => $config['filesystem']['acl'],
                    ]
                )
            ;

            if (3 === $config['filesystem']['sdk_version']) {
                $arguments = [
                    'region' => $config['filesystem']['region'],
                    'version' => $config['filesystem']['version'],
                ];

                if (isset($config['filesystem']['secretKey'], $config['filesystem']['accessKey'])) {
                    $arguments['credentials'] = [
                        'secret' => $config['filesystem']['secretKey'],
                        'key' => $config['filesystem']['accessKey'],
                    ];
                }

                if (isset($config['filesystem']['endpoint'])) {
                    $arguments['endpoint'] = $config['filesystem']['endpoint'];
                } else {
                    $arguments['endpoint'] = sprintf(
                        'https://%s.digitaloceanspaces.com',
                        $config['filesystem']['region']
                    );
                }

                $container->getDefinition('bkstg.core.adapter.service.do_spaces')
                    ->replaceArgument(0, $arguments)
                ;
            } else {
                $container->getDefinition('bkstg.core.adapter.service.do_spaces')
                    ->replaceArgument(0, [
                        'secret' => $config['filesystem']['secretKey'],
                        'key' => $config['filesystem']['accessKey'],
                    ])
                ;
            }
        } else {
            $container->removeDefinition('bkstg.core.adapter.filesystem.do_spaces');
            $container->removeDefinition('bkstg.core.filesystem.do_spaces');
        }
    }

    /**
     * Configure the CDN adapter.
     *
     * @param ContainerBuilder $container The container builder.
     * @param array            $config    The configuration.
     *
     * @return void
     */
    public function configureCdnAdapter(ContainerBuilder $container, array $config): void
    {
        if ($container->hasDefinition('bkstg.core.cdn.private_do_spaces') && isset($config['cdn'])) {
            $container
                ->getDefinition('bkstg.core.cdn.private_do_spaces')
                ->replaceArgument(0, $config['cdn']['bucket'])
                ->replaceArgument(1, new Reference($config['cdn']['client']))
            ;
        } else {
            $container->removeDefinition('bkstg.core.cdn.private_do_spaces');
        }
    }
}
