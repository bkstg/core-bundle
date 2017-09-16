<?php

namespace Bkstg\CoreBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer;

class MenuRenderer extends ListRenderer
{
    protected function renderLabel(ItemInterface $item, array $options)
    {
        $rendered_icon = '';
        if ($item instanceof MenuItem && $item->hasIcon()) {
            $rendered_icon = '<i class="fa fa-'.$this->escape($item->getIcon()).'" aria-hidden="true"></i> ';
        }

        return $rendered_icon.parent::renderLabel($item, $options);
    }
}
