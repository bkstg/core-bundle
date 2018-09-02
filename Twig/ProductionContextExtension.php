<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Twig;

use Bkstg\CoreBundle\Context\ProductionContextProviderInterface;
use Bkstg\CoreBundle\Entity\Production;

class ProductionContextExtension extends \Twig_Extension
{
    private $context_provider;

    /**
     * Create new production context extension.
     *
     * @param ProductionContextProviderInterface $context_provider The context provider service.
     */
    public function __construct(ProductionContextProviderInterface $context_provider)
    {
        $this->context_provider = $context_provider;
    }

    /**
     * Return a set of twig functions.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('current_production', [$this, 'getProduction']),
        ];
    }

    /**
     * Get the current poduction from context.
     *
     * @return ?Production
     */
    public function getProduction(): ?Production
    {
        return $this->context_provider->getContext();
    }
}
