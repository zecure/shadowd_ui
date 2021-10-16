<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swd\AnalyzerBundle\Form\Type\UserFilterType;
use Swd\AnalyzerBundle\Entity\UserFilter;
use Swd\AnalyzerBundle\Form\Type\UserType;
use Swd\AnalyzerBundle\Entity\User;
use Swd\AnalyzerBundle\Form\Type\UserSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\Setting;

class UserController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* Handle filter form. */
        $filter = new UserFilter();
        $form = $this->createForm(UserFilterType::class, $filter);

        if ($request->getMethod() === 'GET') {
            $form->handleRequest($request);
        } else {
            $form->submit($request->query->get($form->getName()));
        }

        /* Handle the other form. */
        $userSelector = new Selector();
        $embeddedForm = $this->createForm(UserSelectorType::class, $userSelector);
        $embeddedForm->handleRequest($request);

        if ($embeddedForm->isValid() && $request->get('selected')) {
            foreach ($request->get('selected') as $id) {
                if ($this->getParameter('demo')) {
                    continue;
                }

                $user = $em->getRepository('SwdAnalyzerBundle:User')->find($id);

                if (!$user) {
                    continue;
                }

                switch ($userSelector->getSubaction()) {
                    case 'delete':
                        if ($user->getSetting()) {
                            $em->remove($user->getSetting());
                        }
                        $em->remove($user);
                        break;
                }
            }

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                /* Save all the changes to the database. */
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The users were updated.'));
            }
        }

        /* Get results from database. */
        $query = $em->getRepository('SwdAnalyzerBundle:User')->findAllFiltered($filter);

        /* Pagination. */
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            array('defaultSortFieldName' => 'u.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
        );

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:User:list.html.twig',
            array(
                'users' => $pagination,
                'form' => $form->createView(),
                'embeddedForm' => $embeddedForm->createView(),
                'limit' => $limit
            )
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        /* Handle form. */
        $user = new User();
        $setting = new Setting();
        $setting->setUser($user);

        $form = $this->createForm(UserType::class, $user, array('validation_groups' => array('Default', 'add')));
        $form->handleRequest($request);

        /* Insert and redirect or show the form. */
        if ($form->isValid()) {
            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->persist($setting);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The user was added.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_users_list'));
        } else {
            return $this->render(
                'SwdAnalyzerBundle:User:show.html.twig',
                array('form' => $form->createView())
            );
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        /* Get user from database. */
        $user = $this->getDoctrine()->getRepository('SwdAnalyzerBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $oldPassword = $user->getPassword();

        /* Handle form. */
        $form = $this->createForm(UserType::class, $user, array('required' => false, 'validation_groups' => array('Default', 'edit')));
        $form->handleRequest($request);

        /* Update and redirect or show the form. */
        if ($form->isValid()) {
            $user->setDate(new \DateTime());

            if (!$user->getPassword()) {
                $user->setPassword($oldPassword, false);
            }

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The user was updated.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_users_list'));
        } else {
            return $this->render(
                'SwdAnalyzerBundle:User:show.html.twig',
                array('form' => $form->createView())
            );
        }
    }
}
