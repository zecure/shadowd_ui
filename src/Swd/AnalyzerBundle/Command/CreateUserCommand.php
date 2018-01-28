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

namespace Swd\AnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Swd\AnalyzerBundle\Entity\User;
use Swd\AnalyzerBundle\Entity\Setting;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swd:user:create')
            ->setDescription('Create a new user.')
            ->addOption(
                'username',
                'U',
                InputOption::VALUE_REQUIRED,
                'Set username.'
            )
            ->addOption(
                'password',
                'P',
                InputOption::VALUE_REQUIRED,
                'Set password.'
            )
            ->addOption(
                'email',
                'M',
                InputOption::VALUE_REQUIRED,
                'Set e-mail.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();
        $userRepository = $entityManager->getRepository(User::class);

        $user = new User();
        $user->setRole(1);

        $setting = new Setting();
        $setting->setUser($user);

        $helper = $this->getHelper('question');

        if ($username = $input->getOption('username')) {
            if ($userRepository->findOneBy(['username' => $username])) {
                $output->writeln('User name exists.');
                return 1;
            } elseif (!$username) {
                $output->writeln('Invalid user name.');
                return 1;
            } else {
                $user->setUsername($username);
            }
        } else {
            while (true) {
                $usernameQuestion = new Question('Please enter your username: ', null);
                $username = $helper->ask($input, $output, $usernameQuestion);

                if ($userRepository->findOneBy(['username' => $username])) {
                    $output->writeln('Username already exists.');
                } elseif (!$username) {
                    $output->writeln('Invalid username.');
                } else {
                    $user->setUsername($username);
                    break;
                }
            }
        }

        if ($password = $input->getOption('password')) {
            if (!$password) {
                $output->writeln('Invalid password.');
                return 1;
            } elseif (strlen($password) < 5) {
                $output->writeln('The password has to be at least 5 characters long.');
                return 1;
            } else {
                $user->setPassword($password);
            }
        } else {
            while (true) {
                $passwordQuestion = new Question('Please enter your password: ', null);
                $passwordQuestion->setHidden(true);
                $passwordQuestion->setHiddenFallback(false);
                $password = $helper->ask($input, $output, $passwordQuestion);

                if (!$password) {
                    $output->writeln('Invalid password.');
                } elseif (strlen($password) < 5) {
                    $output->writeln('The password has to be at least 5 characters long.');
                } else {
                    $passwordConfirmationQuestion = new Question('Please confirm your password: ', null);
                    $passwordConfirmationQuestion->setHidden(true);
                    $passwordConfirmationQuestion->setHiddenFallback(false);
                    $passwordConfirmation = $helper->ask($input, $output, $passwordConfirmationQuestion);

                    if ($password === $passwordConfirmation) {
                        $user->setPassword($password);
                        break;
                    } else {
                        $output->writeln('The passwords do not match.');
                    }
                }
            }
        }

        if ($email = $input->getOption('email')) {
            if ($userRepository->findOneBy(['email' => $email])) {
                $output->writeln('E-mail exists.');
                return 1;
            } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $output->writeln('Invalid e-mail.');
                return 1;
            } else {
                $user->setEmail($email);
            }
        } else {
            while (true) {
                $emailQuestion = new Question('Please enter your e-mail: ', null);
                $email = $helper->ask($input, $output, $emailQuestion);

                if ($userRepository->findOneBy(['email' => $email])) {
                    $output->writeln('E-mail exists.');
                } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $output->writeln('Invalid e-mail.');
                } else {
                    $user->setEmail($email);
                    break;
                }
            }
        }

        $entityManager->persist($user);
        $entityManager->persist($setting);
        $entityManager->flush();

        $output->writeln('Created user ' . $user->getUsername());

        return 0;
    }
}
