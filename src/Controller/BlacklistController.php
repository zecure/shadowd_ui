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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\BlacklistRuleFilterType;
use App\Entity\BlacklistRuleFilter;
use App\Form\Type\BlacklistRuleType;
use App\Entity\BlacklistRule;
use App\Form\Type\BlacklistRuleSelectorType;
use App\Entity\Selector;
use App\Entity\BlacklistImport;
use App\Form\Type\BlacklistImportType;
use App\Entity\BlacklistExport;
use App\Form\Type\BlacklistExportType;

class BlacklistController extends AbstractController
{
    /**
     * @Route("/blacklist/rules", name="swd_analyzer_blacklist_rules")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* Handle filter form. */
        $filter = new BlacklistRuleFilter();
        $form = $this->createForm(BlacklistRuleFilterType::class, $filter);

        if ($request->getMethod() === 'GET') {
            $form->handleRequest($request);
        } else {
            $form->submit($request->query->get($form->getName()));
        }

        /* Handle the form that is embedded in the table. */
        $ruleSelector = new Selector();
        $embeddedForm = $this->createForm(BlacklistRuleSelectorType::class, $ruleSelector);
        $embeddedForm->handleRequest($request);

        if ($embeddedForm->isSubmitted() && $embeddedForm->isValid() && $request->get('selected')) {
            /* Check user permissions, just in case. */
            if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException($this->get('translator')->trans('Unable to modify rules.'));
            }

            foreach ($request->get('selected') as $id) {
                if ($this->getParameter('demo')) {
                    continue;
                }

                $rule = $em->getRepository(BlacklistRule::class)->find($id);

                if (!$rule) {
                    continue;
                }

                switch ($ruleSelector->getSubaction()) {
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

                /* Update the modification date for security. */
                $rule->setDate(new \DateTime());

                /* Mark the cache as outdated. */
                $rule->getProfile()->setCacheOutdated(1);
            }

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                /* Save all the changes to the database. */
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rules were updated.'));
            }
        }

        /* Get results from database. */
        $query = $em->getRepository(BlacklistRule::class)->findAllFiltered($filter);

        /* Pagination. */
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            [
                'defaultSortFieldName' => 'br.id',
                'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText()
            ]
        );

        /* Mark conflicts. */
        foreach ($pagination as $rule) {
            $rule->setConflict($em->getRepository(BlacklistRule::class)->findConflict($rule)->getSingleScalarResult());
        }

        /* Render template. */
        return $this->render(
            'Blacklist:list.html.twig',
            [
                'rules' => $pagination,
                'form' => $form->createView(),
                'embeddedForm' => $embeddedForm->createView(),
                'limit' => $limit
            ]
        );
    }

    /**
     * @Route("/blacklist/rule", name="swd_analyzer_blacklist_add_rule")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        /* Handle form. */
        $rule = new BlacklistRule();
        $form = $this->createForm(BlacklistRuleType::class, $rule);
        $form->handleRequest($request);

        /* Insert and redirect or show the form. */
        if ($form->isValid()) {
            $rule->getProfile()->setCacheOutdated(1);

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($rule);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was added.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
        } else {
            return $this->render(
                'Blacklist:show.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("/blacklist/rule/{id}", name="swd_analyzer_blacklist_edit_rule")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        /* Get rule from database. */
        $rule = $this->getDoctrine()->getRepository(BlacklistRule::class)->find($id);

        if (!$rule) {
            throw $this->createNotFoundException('No rule found for id ' . $id);
        }

        /* Handle form. */
        $form = $this->createForm(BlacklistRuleType::class, $rule);
        $form->handleRequest($request);

        /* Update and redirect or show the form. */
        if ($form->isValid()) {
            $rule->setDate(new \DateTime());
            $rule->getProfile()->setCacheOutdated(1);

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($rule);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was updated.'));
            }
            return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
        } else {
            return $this->render(
                'Blacklist:show.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("/blacklist/import", name="swd_analyzer_blacklist_import")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importAction(Request $request)
    {
        /* Handle form. */
        $import = new BlacklistImport();
        $form = $this->createForm(BlacklistImportType::class, $import);
        $form->handleRequest($request);

        /* Insert and redirect or show the form. */
        if ($form->isValid()) {
            $fileContent = file_get_contents($import->getFile()->getPathName());
            $rules = json_decode($fileContent, true);

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else if ($rules) {
                $import->getProfile()->setCacheOutdated(1);

                $em = $this->getDoctrine()->getManager();

                foreach ($rules as $rule) {
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
            } else {
                $this->get('session')->getFlashBag()->add('alert', $this->get('translator')->trans('Invalid file.'));
            }

            return $this->redirect($this->generateUrl('swd_analyzer_blacklist_rules'));
        } else {
            /* Render template. */
            return $this->render(
                'Blacklist:import.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("/blacklist/export", name="swd_analyzer_blacklist_export")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportAction(Request $request)
    {
        /* Handle form. */
        $export = new BlacklistExport();
        $form = $this->createForm(BlacklistExportType::class, $export);
        $form->handleRequest($request);

        /* Start download or show form. */
        if ($form->isValid()) {
            /* Gather rules in the export format. */
            $em = $this->getDoctrine()->getManager();
            $rules = $em->getRepository(BlacklistRule::class)->findAllByExport($export)->getResult();

            $rulesJson = [];

            foreach ($rules as $rule) {
                $ruleJson['path'] = $rule->getPath();
                $ruleJson['caller'] = $rule->getCaller();

                if ($export->getBase()) {
                    $ruleJson['caller'] = str_replace($export->getBase(), '{BASE}', $ruleJson['caller']);
                }

                $ruleJson['threshold'] = $rule->getThreshold();

                $rulesJson[] = $ruleJson;
            }

            /* Create a download response. */
            $response = new Response(
                json_encode($rulesJson, JSON_PRETTY_PRINT),
                Response::HTTP_OK,
                [
                    'content-type' => 'text/plain'
                ]
            );

            $disposition = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                date('Ymd_His') . '_shadowd_blacklist.txt'
            );

            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        } else {
            /* Render template. */
            return $this->render(
                'Blacklist:export.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}
