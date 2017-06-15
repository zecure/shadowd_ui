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

namespace Swd\AnalyzerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class ProfileSelectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subaction', ChoiceType::class, array('choices' => array(
                'enablewhitelist' => 'Enable whitelist',
                'disablewhitelist' => 'Disable whitelist',
                'enableblacklist' => 'Enable blacklist',
                'disableblacklist' => 'Disable blacklist',
                'enableintegrity' => 'Enable integrity',
                'disableintegrity' => 'Disable integrity',
                'enableflooding' => 'Enable flooding',
                'disableflooding' => 'Disable flooding',
                'deletelearning' => 'Delete learning requests',
                'deleteproductive' => 'Delete productive requests',
                'delete' => 'Delete profiles'
            )))
            ->add('actions', FormActionsType::class, array('buttons' => array('do' => array(
                'type' => SubmitType::class, 'options' => array('label' => 'Execute'))
            )));
    }

    public function getName()
    {
        return 'profile_selector';
    }
}
