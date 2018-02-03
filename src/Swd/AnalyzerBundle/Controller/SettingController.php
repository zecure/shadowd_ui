<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2018 Hendrik Buchwald <hb@zecure.org>
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
use Swd\AnalyzerBundle\Form\Type\SettingType;
use Swd\AnalyzerBundle\Entity\Setting;

class SettingController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        /* Get rule from database. */
        $settings = $user->getSetting();

        /* Handle form. */
        $form = $this->createForm(SettingType::class, $settings);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $request->setLocale($settings->getLocale());
            $this->get('session')->set('_locale', $settings->getLocale());

            if ($this->getParameter('demo')) {
                $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The demo is read-only, no changes were saved.'));
            } else {
                if ($settings->getOldPassword()) {
                    if (!$settings->getNewPassword()) {
                        $this->get('session')->getFlashBag()->add('alert', $this->get('translator')->trans('The new password can not be empty.'));
                    } elseif (!password_verify($settings->getOldPassword(), $user->getPassword())) {
                        $this->get('session')->getFlashBag()->add('alert', $this->get('translator')->trans('The old password is not correct.'));
                    } else {
                        $user->setPassword($settings->getNewPassword());
                        $user->setChangePassword(false);
                        $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The settings and password were updated.'));
                    }
                } else {
                    $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('The settings were updated.'));
                }

                $this->getDoctrine()->getManager()->flush();
            }
        }

        /* Render template. */
        return $this->render(
            'SwdAnalyzerBundle:Setting:index.html.twig', array(
                'form' => $form->createView()
            )
        );
    }
}
