<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
 *
 * This file is part of Shadow Daemon. Shadow Daemon is free software: you can
 * redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;

class Builder
{
    private $factory;
    private $translator;
    private $security;

    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, Security $security)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->security = $security;
    }

    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        if ($this->security->isGranted('ROLE_USER')) {
            $menu->addChild(
                '<i class="menu-icons fa fa-home"></i>' . $this->translator->trans('Home'),
                array('route' => 'swd_analyzer_home', 'extras' => array('safe_label' => true)));
            $analysis = $menu->addChild(
                '<i class="menu-icons fa fa-binoculars"></i>' . $this->translator->trans('Analysis'),
                array('extras' => array('safe_label' => true)));
            $analysis->addChild(
                '<i class="menu-icons fa fa-cog"></i>' . $this->translator->trans('Requests'),
                array('route' => 'swd_analyzer_requests_list', 'extras' => array('safe_label' => true)));
            $analysis->addChild(
                '<i class="menu-icons fa fa-cogs"></i>' . $this->translator->trans('Parameters'),
                array('route' => 'swd_analyzer_parameters_list', 'extras' => array('safe_label' => true)));
            $management = $menu->addChild(
                '<i class="menu-icons fa fa-balance-scale"></i>' . $this->translator->trans('Management'),
                array('extras' => array('safe_label' => true)));
            $management->addChild(
                '<i class="menu-icons fa fa-cubes"></i>' . $this->translator->trans('Profiles'),
                array('route' => 'swd_analyzer_profiles_list', 'extras' => array('safe_label' => true)));
            $management->addChild('management_divider1')->setAttribute('divider', true);
            $management->addChild(
                '<i class="menu-icons fa fa-square"></i>' . $this->translator->trans('Blacklist'),
                array('route' => 'swd_analyzer_blacklist_rules', 'extras' => array('safe_label' => true)));
            $management->addChild(
                '<i class="menu-icons fa fa-square-o"></i>' . $this->translator->trans('Whitelist'),
                array('route' => 'swd_analyzer_whitelist_rules', 'extras' => array('safe_label' => true)));
            $management->addChild(
                '<i class="menu-icons fa fa-shield"></i>' . $this->translator->trans('Integrity'),
                array('route' => 'swd_analyzer_integrity_rules', 'extras' => array('safe_label' => true)));

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $administration = $menu->addChild(
                    '<i class="menu-icons fa fa-university"></i>' . $this->translator->trans('Administration'),
                    array('extras' => array('safe_label' => true)));
                $administration->addChild(
                    '<i class="menu-icons fa fa-users"></i>' . $this->translator->trans('Users'),
                    array('route' => 'swd_analyzer_users_list', 'extras' => array('safe_label' => true)));
                $administration->addChild(
                    '<i class="menu-icons fa fa-share-alt"></i>' . $this->translator->trans('Generator'),
                    array('route' => 'swd_analyzer_generator', 'extras' => array('safe_label' => true)));
            }

            $user = $menu->addChild(
                '<i class="menu-icons fa fa-user"></i>' . $this->translator->trans('User'),
                array('extras' => array('safe_label' => true)));
            $user->addChild(
                '<i class="menu-icons fa fa-wrench"></i>' . $this->translator->trans('Settings'),
                array('route' => 'swd_analyzer_settings', 'extras' => array('safe_label' => true)));
            $user->addChild(
                '<i class="menu-icons fa fa-sign-out"></i>' . $this->translator->trans('Logout'),
                array('route' => 'app_logout', 'extras' => array('safe_label' => true)));
        }

        return $menu;
    }
}
