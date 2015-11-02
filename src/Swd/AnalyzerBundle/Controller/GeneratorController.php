<?php

/*
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

namespace Swd\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\GeneratorSettings;
use Swd\AnalyzerBundle\Form\Type\GeneratorSettingsType;

class GeneratorController extends Controller
{
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function indexAction()
	{
		/* Handle form. */
		$settings = new GeneratorSettings();
		$form = $this->createForm(new GeneratorSettingsType(), $settings);
		$form->handleRequest($this->get('request'));

		/* Insert and redirect or show the form. */
		if ($form->isValid())
		{
			// TODO

			/*$learner = $this->get('generator_manager');
			$learner->generateStatistics($settings);
			$learner->generateRules($settings);
			$counter = $learner->persistRules();

			if ($counter === 0)
			{
				$this->get('session')->getFlashBag()->add('info', 'No new rules were added.');
			}
			elseif ($counter === 1)
			{
				$this->get('session')->getFlashBag()->add('info', 'One new rule was added.');
			}
			else
			{
				$this->get('session')->getFlashBag()->add('info', $counter . ' new rules were added.');
			}*/

			return $this->redirect($this->generateUrl('swd_analyzer_whitelist_rules'));
		}
		else
		{
			/* Render template. */
			return $this->render(
				'SwdAnalyzerBundle:Generator:index.html.twig',
				array(
					'form' => $form->createView()
				)
			);
		}
	}
}
