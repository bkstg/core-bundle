<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ConfigureAmazonMetaPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ContainerBuilder $container The container builder.
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $config = $this->getExtensionConfig($container);

        if ($container->hasDefinition('bkstg.core.filesystem.do_spaces')) {
            $container->getDefinition('sonata.media.metadata.amazon')
                ->addArgument([
                        'acl' => $config['filesystem']['acl'],
                        'storage' => $config['filesystem']['storage'],
                        'encryption' => $config['filesystem']['encryption'],
                        'meta' => $config['filesystem']['meta'],
                        'cache_control' => $config['filesystem']['cache_control'],
                ])
            ;
        }
    }

    /**
     * @param ContainerBuilder $container The container builder.
     *
     * @return array
     */
    private function getExtensionConfig(ContainerBuilder $container): array
    {
        $config = $container->getExtensionConfig('bkstg_core');
        $config = $container->getParameterBag()->resolveValue($config);
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $config);
    }
}
