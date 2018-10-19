<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @return TreeBuilder The configuration tree.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bkstg_core');

        $rootNode
            ->children()
                ->arrayNode('cdn')
                    ->children()
                        ->scalarNode('bucket')->isRequired()->end()
                        ->scalarNode('client')->defaultValue('bkstg.core.adapter.service.do_spaces')->end()
                    ->end()
                ->end()
                ->arrayNode('filesystem')
                    ->children()
                        ->scalarNode('directory')->defaultValue('')->end()
                        ->scalarNode('bucket')->isRequired()->end()
                        ->scalarNode('accessKey')->isRequired()->end()
                        ->scalarNode('secretKey')->isRequired()->end()
                        ->scalarNode('region')->isRequired()->end()
                        ->scalarNode('endpoint')->defaultNull()->end()
                        ->scalarNode('create')->defaultValue(false)->end()
                        ->scalarNode('storage')
                            ->defaultValue('standard')
                            ->validate()
                            ->ifNotInArray(['standard', 'reduced'])
                                ->thenInvalid('Invalid storage type - "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('cache_control')->defaultValue('')->end()
                        ->scalarNode('acl')
                            ->defaultValue('public')
                            ->validate()
                            ->ifNotInArray([
                                'private',
                                'public',
                                'open',
                                'auth_read',
                                'owner_read',
                                'owner_full_control',
                            ])
                                ->thenInvalid('Invalid acl permission - "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('encryption')
                            ->defaultValue('')
                            ->validate()
                            ->ifNotInArray(['aes256'])
                                ->thenInvalid('Invalid encryption type - "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('version')->defaultValue('latest')->end()
                        ->enumNode('sdk_version')
                            ->values([2, 3])
                            ->defaultValue(3)
                        ->end()
                        ->arrayNode('meta')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
