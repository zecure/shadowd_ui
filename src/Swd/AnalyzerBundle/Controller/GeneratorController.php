<?php

/*
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2017 Hendrik Buchwald <hb@zecure.org>
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
use Swd\AnalyzerBundle\Entity\Selector;
use Swd\AnalyzerBundle\Entity\GeneratorSettings;
use Swd\AnalyzerBundle\Form\Type\GeneratorSettingsType;

class GeneratorController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        /* Handle form. */
        $settings = new GeneratorSettings();
        $form = $this->createForm(GeneratorSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $generator = $this->get('generator_manager');
            $generator->start($settings);
            $counter = $generator->save();

            if ($counter === 0) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('No new rules were added.'));
            } elseif ($counter === 1) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('One new rule was added.'));
            } else {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('%number% new rules were added.', array('%number%' => $counter)));
            }
        }

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:Generator:index.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
