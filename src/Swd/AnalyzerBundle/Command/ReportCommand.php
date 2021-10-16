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

namespace Swd\AnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swd\AnalyzerBundle\Entity\Request;
use Swd\AnalyzerBundle\Entity\User;

class ReportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swd:report')
            ->setDescription('Send a report about recent attacks via email')
            ->addOption(
                'time_frame',
                't',
                InputOption::VALUE_OPTIONAL,
                'Set the time frame of the report',
                '-24 hours'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Get requests in the desired time frame. */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $date = new \DateTime($input->getOption('time_frame'));
        $requests = $em->getRepository('SwdAnalyzerBundle:Request')->findByDate($date)->getResult();

        if (empty($requests)) {
            if ($output->isVerbose()) {
                $output->writeln('No requests found');
            }

            return;
        }

        /* Send e-mails to all users that have specified an address. */
        $users = $em->getRepository('SwdAnalyzerBundle:User')->findByEmail()->getResult();

        if (empty($users)) {
            if ($output->isVerbose()) {
                $output->writeln('No e-mail addresses found');
            }

            return;
        }

        foreach ($users as $user) {
            if ($output->isVerbose()) {
                $output->writeln('Send email to ' . $user->getEmail());
            }

            $message = \Swift_Message::newInstance()
                ->setSubject('Shadow Daemon Report')
                ->setFrom('noreply@zecure.org')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->getContainer()->get('templating')->render(
                        'SwdAnalyzerBundle:Report:email.txt.twig',
                        array('requests' => $requests)
                    )
                );

            $this->getContainer()->get('mailer')->send($message);
        }
    }
}
