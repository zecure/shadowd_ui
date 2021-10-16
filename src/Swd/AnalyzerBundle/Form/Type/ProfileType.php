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

namespace Swd\AnalyzerBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serverIP', null, array('label' => 'Server IP'))
            ->add('name')
            ->add('key', PasswordType::class, array('attr' => array('autocomplete' => 'off')))
            ->add('mode', ChoiceType::class, array('choices' => array('1' => 'Active', '2' => 'Passive', '3' => 'Learning')))
            ->add('whitelistEnabled', ChoiceType::class, array('label' => 'Whitelist', 'choices' => array('1' => 'Enabled', '0' => 'Disabled')))
            ->add('blacklistEnabled', ChoiceType::class, array('label' => 'Blacklist', 'choices' => array('1' => 'Enabled', '0' => 'Disabled')))
            ->add('integrityEnabled', ChoiceType::class, array('label' => 'Integrity', 'choices' => array('1' => 'Enabled', '0' => 'Disabled')))
            ->add('floodingEnabled', ChoiceType::class, array('label' => 'Flooding', 'choices' => array('1' => 'Enabled', '0' => 'Disabled')))
            ->add('blacklistThreshold', null, array('label' => 'Global threshold'))
            ->add('floodingTime', null, array('label' => 'Timeframe'))
            ->add('floodingThreshold', null, array('label' => 'Threshold'))
            ->add('actions', FormActionsType::class, array('buttons' => array('save' => array('type' => SubmitType::class), 'reset' => array('type' => ResetType::class))));
    }

    public function getName()
    {
        return 'profile';
    }
}
