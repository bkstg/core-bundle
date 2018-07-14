<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\Menu\Matcher\PathAncestorMatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddMenuVotersPass implements CompilerPassInterface
{
    /**
     * Add the menu voters to the path ancestor matcher.
     *
     * @param ContainerBuilder $container The container builder.
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        // The the path ancestor matcher service.
        $definition = $container->getDefinition(PathAncestorMatcher::class);

        // Find the menu voter services and add them.
        $voters = [];
        foreach ($container->findTaggedServiceIds('knp_menu.voter') as $id => $tags) {
            $tag = $tags[0];

            $priority = isset($tag['priority']) ? (int) $tag['priority'] : 0;
            $voters[$priority][] = new Reference($id);
        }

        // Jump out if there are no voter services.
        if (empty($voters)) {
            return;
        }

        // Sort the voters and add them to the matcher.
        krsort($voters);
        $sortedVoters = call_user_func_array('array_merge', $voters);
        $definition->replaceArgument(0, $sortedVoters);
    }
}
