<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2016 Hendrik Buchwald <hb@zecure.org>
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
use Swd\AnalyzerBundle\Form\Type\IgnoreType;
use Swd\AnalyzerBundle\Entity\ParameterFilter;
use Swd\AnalyzerBundle\Form\Type\ParameterSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;

class ParameterController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        /* Handle filter form. */
        $filter = new ParameterFilter();
        $form = $this->createForm(new ParameterFilterType(), $filter);

        if ($this->get('request')->getMethod() === 'GET') {
            $form->handleRequest($this->get('request'));
        } else {
            $form->submit($this->get('request')->query->get($form->getName()));
        }

        /* Handle the other form. */
        $parameterSelector = new Selector();
        $embeddedForm = $this->createForm(new ParameterSelectorType(), $parameterSelector);
        $embeddedForm->handleRequest($this->get('request'));

        if ($embeddedForm->isValid() && $this->get('request')->get('selected'))
        {
            /* Check user permissions, just in case. */
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                throw $this->createAccessDeniedException($this->get('translator')->trans('Unable to modify parameters.'));
            }

            foreach ($this->get('request')->get('selected') as $id)
            {
                $parameter = $em->getRepository('SwdAnalyzerBundle:Parameter')->find($id);

                if (!$parameter)
                {
                    continue;
                }

                switch ($parameterSelector->getSubaction())
                {
                    case 'delete':
                        $em->remove($parameter);
                        break;
                }
            }

            /* Save all the changes to the database. */
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The parameters were updated.'));
        }

        /* Get results from database. */
        $query = $em->getRepository('SwdAnalyzerBundle:Parameter')->findAllFiltered($filter);

        /* Pagination. */
        $page = $this->get('request')->query->get('page', 1);
        $limit = $this->get('request')->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            array('defaultSortFieldName' => 'p.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
        );

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:Parameter:list.html.twig',
            array(
                'parameters' => $pagination,
                'form' => $form->createView(),
                'embeddedForm' => $embeddedForm->createView(),
                'limit' => $limit
            )
        );
    }

    public function showAction($id)
    {
        /* Get results from database. */
        $parameter = $this->getDoctrine()
            ->getRepository('SwdAnalyzerBundle:Parameter')
            ->find($id);

        if (!$parameter)
        {
            throw $this->createNotFoundException('No parameter found for id ' . $id);
        }

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:Parameter:show.html.twig',
            array(
                'id' => $id,
                'parameter' => $parameter
            )
        );
    }
}
