<?php

namespace Bkstg\CoreBundle;

use Bkstg\CoreBundle\DependencyInjection\Compiler\AddMenuVotersPass;
use Bkstg\CoreBundle\DependencyInjection\Compiler\ReplaceMenuMatcherPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BkstgCoreBundle extends Bundle
{
    const TRANSLATION_DOMAIN = 'BkstgCoreBundle';

    /**
     * Add the menu matcher compiler.
     *
     * @param  ContainerBuilder $container The container.
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddMenuVotersPass());
        $container->addCompilerPass(new ReplaceMenuMatcherPass());
    }
}
