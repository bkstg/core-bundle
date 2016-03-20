<?php

namespace Bkstg\CoreBundle\Menu;

use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));

        $dispatcher = $this->container->get('event_dispatcher');
        $event = new MenuCollectionEvent($menu);
        $dispatcher->dispatch(MenuCollectionEvent::NAME, $event);

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav navbar-right',
            ),
        ));

        $menu->addChild('User', array(
            'extras' => array('safe_label' => true),
            'uri' => '#',
            'label' => 'Logged in as ' . $user . ' <span class="caret"></span>',
            'attributes' => array(
                'class' => 'dropdown',
            ),
            'childrenAttributes' => array(
                'class' => 'dropdown-menu',
                'role' => 'menu',
            ),
        ));

        $menu['User']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['User']->setLinkAttribute('data-toggle', 'dropdown');
        $menu['User']->setLinkAttribute('role', 'button');
        $menu['User']->setLinkAttribute('aria-expanded', 'false');

        $menu['User']->addChild('My profile', array(
            'extras' => array('safe_label' => true),
            'label' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp; My profile',
            'route' => 'bkstg_user_view_user',
            'routeParameters' => array('user' => $user->getId()),
        ));
        $menu['User']->addChild('Edit my profile', array(
            'extras' => array('safe_label' => true),
            'label' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp; Edit my profile',
            'route' => 'bkstg_user_edit_profile_user',
            'routeParameters' => array('user' => $user->getId()),
        ));
        $menu['User']->addChild('Change my password', array(
            'extras' => array('safe_label' => true),
            'label' => '<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp; Change my password',
            'route' => 'fos_user_change_password',
        ));
        $menu['User']->addChild('Logout', array(
            'extras' => array('safe_label' => true),
            'label' => '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp; Logout',
            'route' => 'fos_user_security_logout',
        ));

        return $menu;
    }
}
