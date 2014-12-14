<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014 Hendrik Buchwald <hb@zecure.org>
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
use Swd\AnalyzerBundle\Entity\User;
use Swd\AnalyzerBundle\Entity\Setting;

class RegisterCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('swd:register')
			->setDescription('Register a new account')
			->addArgument('name', InputArgument::REQUIRED, 'Login name')
			->addArgument('email', InputArgument::REQUIRED, 'E-mail address')
			->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user will be an admin');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dialog = $this->getHelperSet()->get('dialog');
		$password = $dialog->askHiddenResponse($output, 'Password: ');

		$user = new User();
		$setting = new Setting();
		$setting->setUser($user);

		$user->setUsername($input->getArgument('name'));
		$user->setEmail($input->getArgument('email'));
		$user->setPassword($password);
		$user->setRole($input->getOption('admin') ? 1 : 0);

		$em = $this->getContainer()->get('doctrine')->getManager();
		$em->persist($user);
		$em->persist($setting);
		$em->flush();

		$output->writeln('User created');
	}
}
