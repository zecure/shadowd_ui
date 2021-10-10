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

use App\Entity\Profile;
use App\Entity\Request;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    private function randomLine($filename)
    {
        $lines = file($filename);
        return $lines[array_rand($lines)];
    }

    /**
     * @Route("/", name="swd_analyzer_home")
     */
    public function indexAction(TranslatorInterface $translator)
    {
        if ($this->getUser()->getChangePassword()) {
            $this->get('session')->getFlashBag()->add('alert', $translator->trans('You are still using the default password. Please change it immediately.'));
            return $this->redirect($this->generateUrl('swd_analyzer_settings'));
        }

        $em = $this->getDoctrine()->getManager();

        /* Get random tooltip. */
        try {
            $locale = $this->get('session')->get('_locale');

            if (!preg_match('/^\w+$/i', $locale)) {
                $locale = 'en';
            }

            $path = $this->get('kernel')->locateResource('tooltips/tooltips.' . $locale . '.txt');
            $tooltip = $this->randomLine($path);
        } catch (\InvalidArgumentException $e) {
            $tooltip = $translator->trans('There are no tooltips :(');
        }

        /** @var RequestRepository $requestRepository */
        $requestRepository = $em->getRepository(Request::class);

        /* Get profile data. */
        $profiles = $em->getRepository(Profile::class)->findAll();

        foreach ($profiles as $profile) {
            $profile->setProductiveRequests(
                $requestRepository->countByProfileAndMode($profile, 1)->getSingleScalarResult() +
                $requestRepository->countByProfileAndMode($profile, 2)->getSingleScalarResult()
            );
            $profile->setLastRequest($requestRepository->findLastByProfile($profile));
        }

        /* Render template. */
        return $this->render(
            'Home/index.html.twig',
            [
                'tooltip' => $tooltip,
                'profiles' => $profiles
            ]
        );
    }
}
