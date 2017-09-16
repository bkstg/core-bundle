<?php

namespace Bkstg\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem as BaseMenuItem;

class MenuItem extends BaseMenuItem
{
    protected $icon;

    public function __construct($name, $icon, FactoryInterface $factory)
    {
        $this->icon = $icon;
        parent::__construct($name, $factory);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function hasIcon()
    {
        return !empty($this->icon);
    }
}
