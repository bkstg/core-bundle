<?php

namespace Bkstg\CoreBundle\Twig;

class Md5Extension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_Function('md5', [$this, 'md5']),
        ];
    }

    public function md5(string $value)
    {
        return md5($value);
    }
}
