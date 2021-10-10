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

namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Form\Extension\Core\Type\ResetType;
//use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class BlacklistRuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('profile', null, ['property' => 'getIdAndName'])
//            ->add('caller')
//            ->add('path')
//            ->add('threshold', null, ['required' => false, 'empty_data' => '-1'])
//            ->add('status', ChoiceType::class, ['choices' => ['1' => 'Activated', '2' => 'Deactivated', '3' => 'Pending']])
//            ->add('actions', FormActionsType::class, ['buttons' => ['save' => ['type' => SubmitType::class], 'reset' => ['type' => ResetType::class]]]);
        ;
    }

    public function getName()
    {
        return 'blacklist_rule';
    }
}
