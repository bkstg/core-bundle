<?php

namespace Bkstg\CoreBundle\DependencyInjection\Compiler;

use Bkstg\CoreBundle\Menu\Matcher\PathAncestorMatcher;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddMenuVotersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(PathAncestorMatcher::class);

        $voters = array();

        foreach ($container->findTaggedServiceIds('knp_menu.voter') as $id => $tags) {
            $tag = $tags[0];

            $priority = isset($tag['priority']) ? (int) $tag['priority'] : 0;
            $voters[$priority][] = new Reference($id);
        }

        if (empty($voters)) {
            return;
        }

        krsort($voters);
        $sortedVoters = call_user_func_array('array_merge', $voters);
        $definition->replaceArgument(0, $sortedVoters);
    }
}
