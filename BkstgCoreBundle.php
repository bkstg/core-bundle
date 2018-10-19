<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle;

use Bkstg\CoreBundle\DependencyInjection\Compiler\AddMenuVotersPass;
use Bkstg\CoreBundle\DependencyInjection\Compiler\ConfigureAmazonMetaPass;
use Bkstg\CoreBundle\DependencyInjection\Compiler\ReplaceMenuMatcherPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BkstgCoreBundle extends Bundle
{
    const TRANSLATION_DOMAIN = 'BkstgCoreBundle';

    /**
     * Add the menu matcher compiler.
     *
     * @param ContainerBuilder $container The container.
     *
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddMenuVotersPass());
        $container->addCompilerPass(new ReplaceMenuMatcherPass());
        $container->addCompilerPass(new ConfigureAmazonMetaPass());
    }
}
