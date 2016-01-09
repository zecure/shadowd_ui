<?php

/*
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Swd\AnalyzerBundle\Form\Type\IntegrityRuleFilterType;
use Swd\AnalyzerBundle\Entity\IntegrityRuleFilter;
use Swd\AnalyzerBundle\Form\Type\IntegrityRuleType;
use Swd\AnalyzerBundle\Entity\IntegrityRule;
use Swd\AnalyzerBundle\Form\Type\IntegrityRuleSelectorType;
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\IntegrityImport;
use Swd\AnalyzerBundle\Form\Type\IntegrityImportType;
use Swd\AnalyzerBundle\Entity\IntegrityExport;
use Swd\AnalyzerBundle\Form\Type\IntegrityExportType;

class IntegrityController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        /* Handle filter form. */
        $filter = new IntegrityRuleFilter();
        $form = $this->createForm(new IntegrityRuleFilterType(), $filter);
        $form->handleRequest($this->get('request'));

        /* Handle the form that is embedded in the table. */
        $ruleSelector = new Selector();
        $embeddedForm = $this->createForm(new IntegrityRuleSelectorType(), $ruleSelector);
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
                $rule = $em->getRepository('SwdAnalyzerBundle:IntegrityRule')->find($id);

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

                /* Update the modification date for security. */
                $rule->setDate(new \DateTime());

                /* Mark the cache as outdated. */
                $rule->getProfile()->setCacheOutdated(1);
            }

            /* Save all the changes to the database. */
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rules were updated.'));
        }

        /* Get results from database. */
        $query = $em->getRepository('SwdAnalyzerBundle:IntegrityRule')->findAllFiltered($filter);

        /* Pagination. */
        $page = $this->get('request')->query->get('page', 1);
        $limit = $this->get('request')->query->get('limit', $this->getUser()->getSetting()->getPageLimit());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            array('defaultSortFieldName' => 'ir.id', 'defaultSortDirection' => $this->getUser()->getSetting()->getSortOrderText())
        );

        /* Mark conflicts. */
        foreach ($pagination as $rule)
        {
            $rule->setConflict($em->getRepository('SwdAnalyzerBundle:IntegrityRule')->findConflict($rule)->getSingleScalarResult());
        }

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:Integrity:list.html.twig',
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
        $rule = new IntegrityRule();
        $form = $this->createForm(new IntegrityRuleType(), $rule);
        $form->handleRequest($this->get('request'));

        /* Insert and redirect or show the form. */
        if ($form->isValid())
        {
            $rule->getProfile()->setCacheOutdated(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($rule);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was added.'));
            return $this->redirect($this->generateUrl('swd_analyzer_integrity_rules'));
        }
        else
        {
            return $this->render(
                'SwdAnalyzerBundle:Integrity:show.html.twig',
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
        $rule = $this->getDoctrine()->getRepository('SwdAnalyzerBundle:IntegrityRule')->find($id);

        if (!$rule)
        {
            throw $this->createNotFoundException('No rule found for id ' . $id);
        }

        /* Handle form. */
        $form = $this->createForm(new IntegrityRuleType(), $rule);
        $form->handleRequest($this->get('request'));

        /* Update and redirect or show the form. */
        if ($form->isValid())
        {
            $rule->setDate(new \DateTime());
            $rule->getProfile()->setCacheOutdated(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($rule);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The rule was updated.'));
            return $this->redirect($this->generateUrl('swd_analyzer_integrity_rules'));
        }
        else
        {
            return $this->render(
                'SwdAnalyzerBundle:Integrity:show.html.twig',
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
        $import = new IntegrityImport();
        $form = $this->createForm(new IntegrityImportType(), $import);
        $form->handleRequest($this->get('request'));

        /* Insert and redirect or show the form. */
        if ($form->isValid())
        {
            $fileContent = file_get_contents($import->getFile()->getPathName());
            $rules = json_decode($fileContent, true);

            if ($rules)
            {
                $import->getProfile()->setCacheOutdated(1);

                $em = $this->getDoctrine()->getManager();

                foreach ($rules as $rule)
                {
                    $ruleObj = new IntegrityRule();
                    $ruleObj->setProfile($import->getProfile());
                    $ruleObj->setCaller(str_replace('{BASE}', $import->getBase(), $rule['caller']));
                    $ruleObj->setAlgorithm($rule['algorithm']);
                    $ruleObj->setDigest($rule['digest']);
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

            return $this->redirect($this->generateUrl('swd_analyzer_integrity_rules'));
        }
        else
        {
            /* Render template. */
            return $this->render(
                'SwdAnalyzerBundle:Integrity:import.html.twig',
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
        $export = new IntegrityExport();
        $form = $this->createForm(new IntegrityExportType(), $export);
        $form->handleRequest($this->get('request'));

        /* Start download or show form. */
        if ($form->isValid())
        {
            /* Gather rules in the export format. */
            $em = $this->getDoctrine()->getManager();
            $rules = $em->getRepository('SwdAnalyzerBundle:IntegrityRule')->findAllByExport($export)->getResult();

            $rulesJson = array();

            foreach ($rules as $rule)
            {
                $ruleJson['caller'] = $rule->getCaller();

                if ($export->getBase())
                {
                    $ruleJson['caller'] = str_replace($export->getBase(), '{BASE}', $ruleJson['caller']);
                }

                $ruleJson['algorithm'] = $rule->getAlgorithm();
                $ruleJson['digest'] = $rule->getDigest();

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
                date('Ymd_His') . '_shadowd_integrity.txt'
            );

            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        }
        else
        {
            /* Render template. */
            return $this->render(
                'SwdAnalyzerBundle:Integrity:export.html.twig',
                array(
                    'form' => $form->createView()
                )
            );
        }
    }
}
