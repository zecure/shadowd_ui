<?php

/*
 * Shadow Daemon -- High-Interaction Web Honeypot
 *
 *   Copyright (C) 2014 Hendrik Buchwald <hb@zecure.org>
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

		$request = Request::createFromGlobals();
		$menu->setCurrentUri($request->getBaseUrl() . $request->getPathInfo());

		if ($this->container->get('security.context')->isGranted('ROLE_USER'))
		{
			$menu->addChild('Home', array('route' => 'swd_analyzer_home'));
			$menu->addChild('Requests', array('route' => 'swd_analyzer_requests_list'));
			$menu->addChild('Parameters', array('route' => 'swd_analyzer_parameters_list'));
			$menu->addChild('Rules', array('route' => 'swd_analyzer_whitelist_rules'));
			$menu->addChild('Profiles', array('route' => 'swd_analyzer_profiles_list'));

			if ($this->container->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				$menu->addChild('Users', array('route' => 'swd_analyzer_users_list'));
			}

			$menu->addChild('Settings', array('route' => 'swd_analyzer_settings'));
			$menu->addChild('Logout', array('route' => 'logout'));
		}

		return $menu;
	}
}
