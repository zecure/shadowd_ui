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

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\Type\ProfileFilterType;
use App\Entity\ProfileFilter;
use App\Form\Type\ProfileType;
use App\Entity\Profile;
use App\Form\Type\ProfileSelectorType;
use App\Entity\Selector;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profiles", name="swd_analyzer_profiles_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* Handle filter form. */
        $filter = new ProfileFilter();
        $form = $this->createForm(ProfileFilterType::class, $filter);

        if ($request->getMethod() === 'GET') {
            $form->handleRequest($request);
        } else {
            $form->submit($request->query->get($form->getName()));
        }

        /* Handle the other form. */
        $profileSelector = new Selector();
        $embeddedForm = $this->createForm(ProfileSelectorType::class, $profileSelector);
        $embeddedForm->handleRequest($request);

        if ($embeddedForm->isValid() && $request->get('selected')) {
            /* Check user permissions, just in case. */
            if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException($this->get('translator')->trans('Unable to modify profiles.'));
            }

            foreach ($request->get('selected') as $id) {
                if ($this->getParameter('demo')) {
                    continue;
                }

                $profile = $em->getRepository('SwdAnalyzerBundle:Profile')->find($id);

                if (!$profile) {
                    continue;
                }

                switch ($profileSelector->getSubaction()) {
                    case 'enablewhitelist':
                        $profile->setWhitelistEnabled(1);
                        break;
                    case 'disablewhitelist':
                        $profile->setWhitelistEnabled(0);
                        break;
                    case 'enableblacklist':
                        $profile->setBlacklistEnabled(1);
                        break;
                    case 'disableblacklist':
                        $profile->setBlacklistEnabled(0);
                        break;
                    case 'enableintegrity':
                        $profile->setIntegrityEnabled(1);
                        break;
                    case 'disableintegrity':
                        $profile->setIntegrityEnabled(0);
                        break;
                    case 'enableflooding':
                        $profile->setFloodingEnabled(1);
                        break;
                    case 'disableflooding':
                        $profile->setFloodingEnabled(0);
                        break;
                    case 'deletelearning':
                        $em->getRepository('SwdAnalyzerBundle:Request')->deleteByProfileAndMode($profile, 3)->getResult();
                        break;
                    case 'deleteproductive':
                        $em->getRepository('SwdAnalyzerBundle:Request')->deleteByProfileAndMode($profile, 1)->getResult();
                        $em->getRepository('SwdAnalyzerBundle:Request')->deleteByProfileAndMode($profile, 2)->getResult();
                        break;
                    case 'delete':
                        $em->remove($profile);
                        break;
                }

                /* Update the modification date for security. */
                $profile->setDate(new \DateTime());
            }

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                /* Save all the changes to the database. */
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The profiles were updated.'));
            }
        }

        /* Get results from database. */
        $query = $em->getRepository('SwdAnalyzerBundle:Profile')->findAllFiltered($filter);

        /* Pagination. */
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            [
                'defaultSortFieldName' => 'v.id',
                'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText()
            ]
        );

        /* Add information about existing learning cache. */
        foreach ($pagination as $profile) {
            $profile->setLearningRequests($em->getRepository('SwdAnalyzerBundle:Request')->countByProfileAndMode($profile, 3)->getSingleScalarResult());
            $profile->setProductiveRequests(
                $em->getRepository('SwdAnalyzerBundle:Request')->countByProfileAndMode($profile, 2)->getSingleScalarResult() +
                $em->getRepository('SwdAnalyzerBundle:Request')->countByProfileAndMode($profile, 1)->getSingleScalarResult()
            );
        }

        /* Render template. */
        return $this->render(
            'Profile:list.html.twig',
            [
                'profiles' => $pagination,
                'form' => $form->createView(),
                'embeddedForm' => $embeddedForm->createView(),
                'limit' => $limit
            ]
        );
    }

    /**
     * @Route("/profile", name="swd_analyzer_profile_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        /* Handle form. */
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile, ['validation_groups' => ['Default', 'add']]);
        $form->handleRequest($request);

        /* Insert and redirect or show the form. */
        if ($form->isValid()) {
            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($profile);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The profile was added.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_profiles_list'));
        } else {
            return $this->render(
                'Profile:show.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("/profile/{id}", name="swd_analyzer_profile_edit")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        /* Get profile from database. */
        $profile = $this->getDoctrine()->getRepository('SwdAnalyzerBundle:Profile')->find($id);

        if (!$profile) {
            throw $this->createNotFoundException('No profile found for id ' . $id);
        }

        $oldKey = $profile->getKey();

        /* Handle form. */
        $form = $this->createForm(ProfileType::class, $profile, ['required' => false, 'validation_groups' => ['Default', 'edit']]);
        $form->handleRequest($request);

        /* Update and redirect or show the form. */
        if ($form->isValid()) {
            $profile->setDate(new \DateTime());

            if (!$profile->getKey()) {
                $profile->setKey($oldKey);
            }

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($profile);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The profile was updated.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_profiles_list'));
        } else {
            return $this->render(
                'Profile:show.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}
