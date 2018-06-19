<?php

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\Menu\Matcher\PathAncestorMatcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ReplaceMenuMatcherPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->removeDefinition('knp_menu.matcher');
        $container->setAlias('knp_menu.matcher', PathAncestorMatcher::class);
    }
}
