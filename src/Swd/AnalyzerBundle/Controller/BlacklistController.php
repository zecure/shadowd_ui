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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Swd\AnalyzerBundle\Form\Type\BlacklistRuleFilterType;
use Swd\AnalyzerBundle\Entity\BlacklistRuleFilter;
use Swd\AnalyzerBundle\Form\Type\BlacklistRuleType;
use Swd\AnalyzerBundle\Entity\BlacklistRule;
use Swd\AnalyzerBundle\Form\Type\BlacklistRuleSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\BlacklistImport;
use Swd\AnalyzerBundle\Form\Type\BlacklistImportType;
use Swd\AnalyzerBundle\Entity\BlacklistExport;
use Swd\AnalyzerBundle\Form\Type\BlacklistExportType;

class BlacklistController extends Controller
{
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();

		/* Handle filter form. */
		$filter = new BlacklistRuleFilter();
		$form = $this->createForm(new BlacklistRuleFilterType(), $filter);
		$form->handleRequest($this->get('request'));

		/* Handle the form that is embedded in the table. */
		$ruleSelector = new Selector();
		$embeddedForm = $this->createForm(new BlacklistRuleSelectorType(), $ruleSelector);
		$embeddedForm->handleRequest($this->get('request'));

		if ($embeddedForm->isValid() && $this->get('request')->get('selected'))
		{
			/* Check user permissions, just in case. */
			if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				throw $this->createAccessDeniedException($this->get('translator')->trans('Unable to modify rules.'));
			}

			foreach ($this->get('request')->get('selected') as $id)
			{
				$rule = $em->getRepository('SwdAnalyzerBundle:BlacklistRule')->find($id);

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

			/* Update the modification date for security. */
			$rule->setDate(new \DateTime());

			/* Save all the changes to the database. */
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rules were updated.'));
		}

		/* Get results from database. */
		$query = $em->getRepository('SwdAnalyzerBundle:BlacklistRule')->findAllFiltered($filter);

		/* Pagination. */
		$page = $this->get('request')->query->get('page', 1);
		$limit = $this->get('request')->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$page,
			$limit,
			array('defaultSortFieldName' => 'br.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
		);

		/* Render template. */
		return $this->render(
			'SwdAnalyzerBundle:Blacklist:list.html.twig',
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
		$rule = new BlacklistRule();
		$form = $this->createForm(new BlacklistRuleType(), $rule);
		$form->handleRequest($this->get('request'));

		/* Insert and redirect or show the form. */
		if ($form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($rule);
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was added.'));
			return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
		}
		else
		{
			return $this->render(
				'SwdAnalyzerBundle:Blacklist:show.html.twig',
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
		$rule = $this->getDoctrine()->getRepository('SwdAnalyzerBundle:BlacklistRule')->find($id);

		if (!$rule)
		{
			throw $this->createNotFoundException('No rule found for id ' . $id);
		}

		/* Handle form. */
		$form = $this->createForm(new BlacklistRuleType(), $rule);
		$form->handleRequest($this->get('request'));

		/* Update and redirect or show the form. */
		if ($form->isValid())
		{
			$rule->setDate(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($rule);
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was updated.'));
			return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
		}
		else
		{
			return $this->render(
				'SwdAnalyzerBundle:Blacklist:show.html.twig',
				array('form' => $form->createView())
			);
		}
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function importAction()
	{
		/* Handle form. */
		$import = new BlacklistImport();
		$form = $this->createForm(new BlacklistImportType(), $import);
		$form->handleRequest($this->get('request'));

		/* Insert and redirect or show the form. */
		if ($form->isValid())
		{
			$fileContent = file_get_contents($import->getFile()->getPathName());
			$rules = json_decode($fileContent, true);

			if ($rules)
			{
				$em = $this->getDoctrine()->getManager();

				foreach ($rules as $rule)
				{
					$ruleObj = new BlacklistRule();
					$ruleObj->setProfile($import->getProfile());
					$ruleObj->setPath($rule['path']);
					$ruleObj->setCaller(str_replace('{BASE}', $import->getBase(), $rule['caller']));
					$ruleObj->setThreshold($rule['threshold']);
					$ruleObj->setStatus(3);

					$em->persist($ruleObj);
				}

				$em->flush();

				$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rules were imported.'));
			}
			else
			{
				$this->get('session')->getFlashBag()->add('alert', $this->get('translator')->trans('Invalid file.'));
			}

			return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
		}
		else
		{
			/* Render template. */
			return $this->render(
				'SwdAnalyzerBundle:Blacklist:import.html.twig',
				array(
					'form' => $form->createView()
				)
			);
		}
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function exportAction()
	{
		/* Handle form. */
		$export = new BlacklistExport();
		$form = $this->createForm(new BlacklistExportType(), $export);
		$form->handleRequest($this->get('request'));

		/* Start download or show form. */
		if ($form->isValid())
		{
			/* Gather rules in the export format. */
			$em = $this->getDoctrine()->getManager();
			$rules = $em->getRepository('SwdAnalyzerBundle:BlacklistRule')->findAllByExport($export)->getResult();

			$rulesJson = array();

			foreach ($rules as $rule)
			{
				$ruleJson['path'] = $rule->getPath();
				$ruleJson['caller'] = $rule->getCaller();

				if ($export->getBase())
				{
					$ruleJson['caller'] = str_replace($export->getBase(), '{BASE}', $ruleJson['caller']);
				}

				$ruleJson['threshold'] = $rule->getThreshold();

				$rulesJson[] = $ruleJson;
			}

			/* Create a download response. */
			$response = new Response(
				json_encode($rulesJson, JSON_PRETTY_PRINT),
				Response::HTTP_OK,
				array('content-type' => 'text/plain')
			);

			$disposition = $response->headers->makeDisposition(
				ResponseHeaderBag::DISPOSITION_ATTACHMENT,
				date('Ymd_His') . '_shadowd_bl_rules.txt'
			);

			$response->headers->set('Content-Disposition', $disposition);

			return $response;
		}
		else
		{
			/* Render template. */
			return $this->render(
				'SwdAnalyzerBundle:Blacklist:export.html.twig',
				array(
					'form' => $form->createView()
				)
			);
		}
	}
}
