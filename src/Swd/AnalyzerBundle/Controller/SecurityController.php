<?php

/**
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
use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        }
        elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR))
        {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = '';
        }

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'SwdAnalyzerBundle:Security:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error
            )
        );
    }
}

?>
