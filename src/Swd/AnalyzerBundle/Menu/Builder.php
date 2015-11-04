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
				'<i class="menu-icons fa fa-home"></i>' . $this->container->get('translator')->trans('Home'),
				array('route' => 'swd_analyzer_home', 'extras' => array('safe_label' => true)));

			$analysis = $menu->addChild(
				'<i class="menu-icons fa fa-binoculars"></i>' . $this->container->get('translator')->trans('Analysis'),
				array('extras' => array('safe_label' => true)));
			$analysis->addChild(
				'<i class="menu-icons fa fa-cog"></i>' . $this->container->get('translator')->trans('Requests'),
				array('route' => 'swd_analyzer_requests_list', 'extras' => array('safe_label' => true)));
			$analysis->addChild(
				'<i class="menu-icons fa fa-cogs"></i>' . $this->container->get('translator')->trans('Parameters'),
				array('route' => 'swd_analyzer_parameters_list', 'extras' => array('safe_label' => true)));
			/*$analysis->addChild('analysis_divider1')->setAttribute('divider', true);
			$analysis->addChild(
				'<i class="menu-icons fa fa-bar-chart"></i>' . $this->container->get('translator')->trans('Statistics'),
				array('route' => 'swd_analyzer_statistics_list', 'extras' => array('safe_label' => true)));*/

			$management = $menu->addChild(
				'<i class="menu-icons fa fa-balance-scale"></i>' . $this->container->get('translator')->trans('Management'),
				array('extras' => array('safe_label' => true)));
			$management->addChild(
				'<i class="menu-icons fa fa-cubes"></i>' . $this->container->get('translator')->trans('Profiles'),
				array('route' => 'swd_analyzer_profiles_list', 'extras' => array('safe_label' => true)));
			$management->addChild('management_divider1')->setAttribute('divider', true);
			$management->addChild(
				'<i class="menu-icons fa fa-square"></i>' . $this->container->get('translator')->trans('Blacklist'),
				array('route' => 'swd_analyzer_blacklist_rules', 'extras' => array('safe_label' => true)));
			$management->addChild(
				'<i class="menu-icons fa fa-square-o"></i>' . $this->container->get('translator')->trans('Whitelist'),
				array('route' => 'swd_analyzer_whitelist_rules', 'extras' => array('safe_label' => true)));
			$management->addChild(
				'<i class="menu-icons fa fa-shield"></i>' . $this->container->get('translator')->trans('Integrity'),
				array('route' => 'swd_analyzer_integrity_rules', 'extras' => array('safe_label' => true)));

			if ($this->container->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				$administration = $menu->addChild(
					'<i class="menu-icons fa fa-university"></i>' . $this->container->get('translator')->trans('Administration'),
					array('extras' => array('safe_label' => true)));
				$administration->addChild(
					'<i class="menu-icons fa fa-users"></i>' . $this->container->get('translator')->trans('Users'),
					array('route' => 'swd_analyzer_users_list', 'extras' => array('safe_label' => true)));
				$administration->addChild(
					'<i class="menu-icons fa fa-share-alt"></i>' . $this->container->get('translator')->trans('Generator'),
					array('route' => 'swd_analyzer_generator', 'extras' => array('safe_label' => true)));
			}

			$user = $menu->addChild(
				'<i class="menu-icons fa fa-user"></i>' . $this->container->get('translator')->trans('User'),
				array('extras' => array('safe_label' => true)));
			$user->addChild(
				'<i class="menu-icons fa fa-wrench"></i>' . $this->container->get('translator')->trans('Settings'),
				array('route' => 'swd_analyzer_settings', 'extras' => array('safe_label' => true)));
			$user->addChild(
				'<i class="menu-icons fa fa-sign-out"></i>' . $this->container->get('translator')->trans('Logout'),
				array('route' => 'logout', 'extras' => array('safe_label' => true)));
		}

		return $menu;
	}
}
