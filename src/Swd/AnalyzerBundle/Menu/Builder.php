<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2015 Hendrik Buchwald <hb@zecure.org>
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

namespace Swd\AnalyzerBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class Builder extends ContainerAware
{
	public function mainMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root');

		if ($this->container->get('security.context')->isGranted('ROLE_USER'))
		{
			$menu->addChild(
				$this->container->get('translator')->trans('Home'),
				array('route' => 'swd_analyzer_home'));

			$analysis = $menu->addChild($this->container->get('translator')->trans('Analysis'));
			$analysis->addChild(
				$this->container->get('translator')->trans('Requests'),
				array('route' => 'swd_analyzer_requests_list'));
			$analysis->addChild(
				$this->container->get('translator')->trans('Parameters'),
				array('route' => 'swd_analyzer_parameters_list'));

			$management = $menu->addChild($this->container->get('translator')->trans('Management'));
			$management->addChild(
				$this->container->get('translator')->trans('Blacklist'),
				array('route' => 'swd_analyzer_blacklist_rules'));
			$management->addChild(
				$this->container->get('translator')->trans('Whitelist'),
				array('route' => 'swd_analyzer_whitelist_rules'));

			$administration = $menu->addChild($this->container->get('translator')->trans('Administration'));
			$administration->addChild(
				$this->container->get('translator')->trans('Profiles'),
				array('route' => 'swd_analyzer_profiles_list'));

			if ($this->container->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				$administration->addChild(
					$this->container->get('translator')->trans('Users'),
					array('route' => 'swd_analyzer_users_list'));
			}

			$user = $menu->addChild($this->container->get('translator')->trans('User'));
			$user->addChild(
				$this->container->get('translator')->trans('Settings'),
				array('route' => 'swd_analyzer_settings'));
			$user->addChild(
				$this->container->get('translator')->trans('Logout'),
				array('route' => 'logout'));
		}

		return $menu;
	}
}
