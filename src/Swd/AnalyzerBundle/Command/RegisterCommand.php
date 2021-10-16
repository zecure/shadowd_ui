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

use Doctrine\Bundle\DoctrineBundle\Registry;
use Swd\AnalyzerBundle\Entity\UserRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swd\AnalyzerBundle\Entity\User;
use Swd\AnalyzerBundle\Entity\Setting;
use Symfony\Component\Console\Question\Question;

class RegisterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swd:register')
            ->setDescription('Register a new account')
            ->addOption(
                'name',
                'N',
                InputOption::VALUE_REQUIRED,
                'Set the login name'
            )
            ->addOption(
                'email',
                'E',
                InputOption::VALUE_OPTIONAL,
                'Set the e-mail address'
            )
            ->addOption(
                'admin',
                'A',
                InputOption::VALUE_NONE,
                'If set the user will be an admin'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (!$input->getOption('name')) {
            $nameQuestion = new Question('Please enter a name: ');
            $input->setOption('name', $helper->ask($input, $output, $nameQuestion));
        }

        /** @var string $name */
        $name = $input->getOption('name');
        /** @var string|null $email */
        $email = $input->getOption('email');

        /** @var Registry $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');
        /** @var UserRepository $userRepository */
        $userRepository = $doctrine->getRepository('SwdAnalyzerBundle:User');

        // XXX: should be handled with validator in the future
        if ($userRepository->findOneBy(['username' => $name]) !== null) {
            $output->writeln('<error>Error:</error> Name already in use');
            return 1;
        } else if ($email && $userRepository->findOneBy(['email' => $email]) !== null) {
            $output->writeln('<error>Error:</error> Email already in use');
            return 1;
        }

        $passwordQuestion = new Question('Please enter a password: ');
        $passwordQuestion->setHidden(true);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $this->createUser(
            $name,
            $password,
            $email,
            $input->getOption('admin') ? 1 : 0
        );

        $output->writeln('User ' . $name . ' created');
        return 0;
    }

    /**
     * @param string $name
     * @param string $password
     * @param string|null $email
     * @param int $role
     */
    private function createUser(string $name, string $password, ?string $email, int $role)
    {
        $user = new User();
        $setting = new Setting();
        $setting->setUser($user);

        $user->setUsername($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);

        /** @var Registry $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->persist($setting);
        $em->flush();
    }
}
