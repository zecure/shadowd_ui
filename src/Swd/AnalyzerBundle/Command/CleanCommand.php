<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2015 Hendrik Buchwald <hb@zecure.org>
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

class CleanCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('swd:clean')
			->setDescription('Delete old requests from the database.')
			->addOption(
				'time_frame',
				'T',
				InputOption::VALUE_OPTIONAL,
				'Set the time frame.',
				'-1 month'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine')->getManager();
		$date = new \DateTime($input->getOption('time_frame'));
		$requests = $em->getRepository('SwdAnalyzerBundle:Request')->deleteByDate($date)->getResult();
	}
}
