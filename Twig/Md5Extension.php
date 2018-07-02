<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Twig;

class Md5Extension extends \Twig_Extension
{
    /**
     * Return a set of twig functions.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('md5', [$this, 'md5']),
        ];
    }

    /**
     * Return an md5 hash for a string.
     *
     * @param string $value The string to hash.
     *
     * @return string
     */
    public function md5(string $value): string
    {
        return md5($value);
    }
}
