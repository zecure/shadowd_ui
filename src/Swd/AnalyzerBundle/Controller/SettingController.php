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

namespace Swd\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Swd\AnalyzerBundle\Form\Type\SettingType;
use Swd\AnalyzerBundle\Entity\Setting;

class SettingController extends Controller
{
	public function indexAction()
	{
		$user = $this->getUser();

		/* Get rule from database. */
		$settings = $user->getSetting();

		/* Handle form. */
		$form = $this->createForm(new SettingType(), $settings);
		$form->handleRequest($this->get('request'));

		if ($form->isValid())
		{
			if ($settings->getOldPassword())
			{
				if (!$settings->getNewPassword())
				{
					$this->get('session')->getFlashBag()->add('alert', 'The new password can not be empty.');
				}
				elseif (!password_verify($settings->getOldPassword(), $user->getPassword()))
				{
					$this->get('session')->getFlashBag()->add('alert', 'The old password is not correct.');
				}
				else
				{
					$user->setPassword($settings->getNewPassword());
					$user->setChangePassword(false);
					$this->get('session')->getFlashBag()->add('info', 'The settings and password were updated.');
				}
			}
			else
			{
				$this->get('session')->getFlashBag()->add('info', 'The settings were updated.');
			}

			$this->getDoctrine()->getManager()->flush();
		}

		/* Render template. */
		return $this->render(
			'SwdAnalyzerBundle:Setting:index.html.twig', array(
				'form' => $form->createView()
			)
		);
	}
}
