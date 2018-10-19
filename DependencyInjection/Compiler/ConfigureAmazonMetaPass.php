<?php

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ConfigureAmazonMetaPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
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
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getExtensionConfig(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('bkstg_core');
        $config = $container->getParameterBag()->resolveValue($config);
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $config);
    }
}
