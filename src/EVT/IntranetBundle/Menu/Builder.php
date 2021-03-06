<?php

namespace EVT\IntranetBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'page-sidebar-menu');

        $menu->addChild(
            $this->container->get('translator')->trans('dashboard'),
            [
                'route' => 'evt_intranet_home_index',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-home');

        $menu->addChild(
            $this->container->get('translator')->trans('leads'),
            [
                'route' => 'evt_intranet_lead_list',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-exchange');

        $menu->addChild(
            $this->container->get('translator')->trans('showrooms'),
            [
                'route' => 'evt_intranet_showroom_list',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-ticket');

        $menu->addChild(
            $this->container->get('translator')->trans('stats'),
            [
                'route' => 'evt_intranet_stats_index',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-bar-chart-o');

        $routeName = 'evt_intranet_managers_list';
        if ($this->container->get('security.context')->isGranted('view', $routeName)) {
            $menu->addChild(
                $this->container->get('translator')->trans('managers'),
                [
                    'route' => $routeName,
                    'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
                ]
            )->setAttribute('icon', 'fa-group');
        }

        $routeName = 'evt_intranet_employees_new';
        if ($this->container->get('security.context')->isGranted('view', $routeName)) {
            $menu->addChild(
                $this->container->get('translator')->trans('employees'),
                [
                    'route' => $routeName,
                    'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
                ]
            )->setAttribute('icon', 'fa-gears');
        }

        return $menu;
    }
}
