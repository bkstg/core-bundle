<?php

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
     * @param  string $value The string to hash.
     * @return string
     */
    public function md5(string $value): string
    {
        return md5($value);
    }
}
