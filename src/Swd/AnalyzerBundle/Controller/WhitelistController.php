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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swd\AnalyzerBundle\Form\Type\WhitelistRuleFilterType;
use Swd\AnalyzerBundle\Entity\WhitelistRuleFilter;
use Swd\AnalyzerBundle\Form\Type\WhitelistRuleType;
use Swd\AnalyzerBundle\Entity\WhitelistRule;
use Swd\AnalyzerBundle\Form\Type\WhitelistRuleSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\GeneratorSettings;
use Swd\AnalyzerBundle\Form\Type\GeneratorSettingsType;

class WhitelistController extends Controller
{
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();

		/* Handle filter form. */
		$filter = new WhitelistRuleFilter();
			$form = $this->createForm(new WhitelistRuleFilterType(), $filter);
			$form->handleRequest($this->get('request'));

		/* Handle the form that is embedded in the table. */
		$ruleSelector = new Selector();
			$embeddedForm = $this->createForm(new WhitelistRuleSelectorType(), $ruleSelector);
			$embeddedForm->handleRequest($this->get('request'));

		if ($embeddedForm->isValid() && $this->get('request')->get('selected'))
		{
			/* Check user permissions, just in case. */
			if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				throw $this->createAccessDeniedException('Unable to modify rules');
			}

			foreach ($this->get('request')->get('selected') as $id)
			{
				$rule = $em->getRepository('SwdAnalyzerBundle:WhitelistRule')->find($id);

				if (!$rule)
				{
					continue;
				}

				switch ($ruleSelector->getSubaction())
				{
					case 'activate':
						$rule->setStatus(1);
						break;
					case 'deactivate':
						$rule->setStatus(2);
						break;
					case 'delete':
						$em->remove($rule);
						break;
				}
			}

			/* Save all the changes to the database. */
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', 'The rules were updated.');
		}

		/* Get results from database. */
		$query = $em->getRepository('SwdAnalyzerBundle:WhitelistRule')->findAllFiltered($filter);

		/* Pagination. */
		$page = $this->get('request')->query->get('page', 1);
		$limit = $this->get('request')->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$page,
			$limit,
			array('defaultSortFieldName' => 'wr.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
		);

		/* Mark conflicts. */
		foreach ($pagination as $rule)
		{
			$rule->setMinLengthConflict($em->getRepository('SwdAnalyzerBundle:WhitelistRule')->findMinLengthConflict($rule)->getSingleScalarResult());
			$rule->setMaxLengthConflict($em->getRepository('SwdAnalyzerBundle:WhitelistRule')->findMaxLengthConflict($rule)->getSingleScalarResult());
			$rule->setFilterConflict($em->getRepository('SwdAnalyzerBundle:WhitelistRule')->findFilterConflict($rule)->getSingleScalarResult());
		}

		/* Render template. */
		return $this->render(
			'SwdAnalyzerBundle:Whitelist:list.html.twig',
			array(
				'rules' => $pagination,
				'form' => $form->createView(),
				'embeddedForm' => $embeddedForm->createView(),
				'limit' => $limit
			)
		);
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction()
	{
		/* Handle form. */
		$rule = new WhitelistRule();
			$form = $this->createForm(new WhitelistRuleType(), $rule);
			$form->handleRequest($this->get('request'));

		/* Insert and redirect or show the form. */
		if ($form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($rule);
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', 'The rule was added.');
			return $this->redirect($this->generateUrl('swd_analyzer_whitelist_rules'));
		}
		else
		{
			return $this->render(
				'SwdAnalyzerBundle:Whitelist:show.html.twig',
				array('form' => $form->createView())
			);
		}
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function editAction($id)
	{
		/* Get rule from database. */
		$rule = $this->getDoctrine()->getRepository('SwdAnalyzerBundle:WhitelistRule')->find($id);

		if (!$rule)
		{
			throw $this->createNotFoundException('No rule found for id ' . $id);
		}

		/* Handle form. */
		$form = $this->createForm(new WhitelistRuleType(), $rule);
		$form->handleRequest($this->get('request'));

		/* Update and redirect or show the form. */
		if ($form->isValid())
		{
			$rule->setDate(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($rule);
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', 'The rule was updated.');
			return $this->redirect($this->generateUrl('swd_analyzer_whitelist_rules'));
		}
		else
		{
			return $this->render(
				'SwdAnalyzerBundle:Whitelist:show.html.twig',
				array('form' => $form->createView())
			);
		}
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function generateAction()
	{
		/* Handle form. */
		$settings = new GeneratorSettings();
		$form = $this->createForm(new GeneratorSettingsType(), $settings);
		$form->handleRequest($this->get('request'));

		/* Insert and redirect or show the form. */
		if ($form->isValid())
		{
			$learner = $this->get('generator_manager');
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
			}

			return $this->redirect($this->generateUrl('swd_analyzer_whitelist_rules'));
		}
		else
		{
			/* Render template. */
			return $this->render(
				'SwdAnalyzerBundle:Whitelist:generate.html.twig',
				array(
					'form' => $form->createView()
				)
			);
		}
	}
}
