<?php

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\Menu\Matcher\PathAncestorMatcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ReplaceMenuMatcherPass implements CompilerPassInterface
{
    /**
     * Replace the menu matcher service with the path ancestor matcher.
     *
     * @param ContainerBuilder $container The container builder.
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $container->removeDefinition('knp_menu.matcher');
        $container->setAlias('knp_menu.matcher', PathAncestorMatcher::class);
    }
}
