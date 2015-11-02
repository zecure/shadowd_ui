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

namespace Swd\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Swd\AnalyzerBundle\Form\Type\RequestFilterType;
use Swd\AnalyzerBundle\Entity\RequestFilter;
use Swd\AnalyzerBundle\Form\Type\RequestSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;

class RequestController extends Controller
{
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();

		/* Handle filter form. */
		$filter = new RequestFilter();
		$form = $this->createForm(new RequestFilterType(), $filter);
		$form->handleRequest($this->get('request'));

		/* Handle the other form. */
		$requestSelector = new Selector();
		$embeddedForm = $this->createForm(new RequestSelectorType(), $requestSelector);
		$embeddedForm->handleRequest($this->get('request'));

		if ($embeddedForm->isValid() && $this->get('request')->get('selected'))
		{
			/* Check user permissions, just in case. */
			if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
			{
				throw $this->createAccessDeniedException($this->get('translator')->trans('Unable to modify requests.'));
			}

			foreach ($this->get('request')->get('selected') as $id)
			{
				$request = $em->getRepository('SwdAnalyzerBundle:Request')->find($id);

				if (!$request)
				{
					continue;
				}

				switch ($requestSelector->getSubaction())
				{
					case 'delete':
						foreach ($request->getParameters() as $parameter)
						{
							$em->remove($parameter);
						}

						$em->remove($request);
						break;
				}
			}

			/* Save all the changes to the database. */
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The requests were updated.'));
		}

		/* Get results from database. */
		$query = $em->getRepository('SwdAnalyzerBundle:Request')->findAllFiltered($filter);

		/* Pagination. */
		$page = $this->get('request')->query->get('page', 1);
		$limit = $this->get('request')->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$page,
			$limit,
			array('defaultSortFieldName' => 'r.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
		);

		/* Render template. */
		return $this->render(
			'SwdAnalyzerBundle:Request:list.html.twig',
			array(
				'requests' => $pagination,
				'form' => $form->createView(),
				'embeddedForm' => $embeddedForm->createView(),
				'limit' => $limit
			)
		);
	}
}
