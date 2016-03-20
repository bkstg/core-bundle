<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuSubscriber implements EventSubscriberInterface
{
    private $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
           MenuCollectionEvent::NAME => array(
               array('addMenuItem', -15),
           )
        );
    }

    public function addMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('Users', array('route' => 'bkstg_user_home'));
        $path = $this->request_stack->getCurrentRequest()->getPathInfo();
        if (preg_match('|^/users|', $path)) {
            $menu['Users']->setAttribute('class', 'active');
        }
    }
}
