<?php

/**
 * Shadow Daemon -- Web Application Firewall
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

namespace Swd\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Swd\AnalyzerBundle\Form\Type\ParameterFilterType;
use Swd\AnalyzerBundle\Entity\ParameterFilter;
use Swd\AnalyzerBundle\Entity\Profile;

class HomeController extends Controller
{
	private function randomLine($filename)
	{
		$lines = file($filename);
		return $lines[array_rand($lines)];
	}

	public function indexAction()
	{
		if ($this->getUser()->getChangePassword())
		{
			$this->get('session')->getFlashBag()->add('alert', 'You are still using the default password. Please change it immediately.');
			return $this->redirect($this->generateUrl('swd_analyzer_settings'));
		}

		$em = $this->getDoctrine()->getManager();

		/* Get random tooltip. */
		$path = $this->get('kernel')->locateResource('@SwdAnalyzerBundle/Resources/translations/tooltips.en.txt');
		$tooltip = $this->randomLine($path);

		/* Get profile data. */
		$profiles = $em->getRepository('SwdAnalyzerBundle:Profile')->findAll();

		/* Render template. */
		return $this->render(
			'SwdAnalyzerBundle:Home:index.html.twig',
			array(
				'tooltip' => $tooltip,
				'profiles' => $profiles
			)
		);
	}
}
