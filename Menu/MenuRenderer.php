<?php

namespace Bkstg\CoreBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class MenuRenderer extends ListRenderer
{
    public function __construct(MatcherInterface $matcher, array $defaultOptions = array(), $charset = null)
    {
        parent::__construct(
            $matcher,
            [
                'currentClass' => 'active',
                'ancestorClass' => 'active ancestor'
            ],
            $charset
        );
    }
    protected function renderLabel(ItemInterface $item, array $options)
    {
        $rendered_icon = '';
        if ($item instanceof MenuItem && $item->hasIcon()) {
            $rendered_icon = '<i class="fa fa-'.$this->escape($item->getIcon()).'" aria-hidden="true"></i> ';
        }

        return $rendered_icon.parent::renderLabel($item, $options);
    }
}
