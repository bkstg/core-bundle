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

class ReplaceMenuMatcherPass implements CompilerPassInterface
{
    /**
     * Replace the menu matcher service with the path ancestor matcher.
     *
     * @param ContainerBuilder $container The container builder.
     */
    public function process(ContainerBuilder $container): void
    {
        $container->removeDefinition('knp_menu.matcher');
        $container->setAlias('knp_menu.matcher', PathAncestorMatcher::class);
    }
}
