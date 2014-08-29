<?php

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
