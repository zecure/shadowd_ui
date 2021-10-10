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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class ProfileFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction('#')
            ->add('includeProfileIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'])
            ->add('includeServerIPs', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'])
            ->add('includeNames', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Name'])
            ->add('includeMode', ChoiceType::class, ['required' => false, 'label' => 'Mode', 'choices' => ['1' => 'Active', '2' => 'Passive', '3' => 'Learning']])
            ->add('includeDateStart', DateTimeType::class, ['required' => false, 'label' => 'From', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('includeDateEnd', DateTimeType::class, ['required' => false, 'label' => 'To', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('excludeProfileIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'])
            ->add('excludeServerIPs', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'])
            ->add('excludeNames', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Name'])
            ->add('excludeMode', ChoiceType::class, ['required' => false, 'label' => 'Mode', 'choices' => ['1' => 'Active', '2' => 'Passive', '3' => 'Learning']])
            ->add('excludeDateStart', DateTimeType::class, ['required' => false, 'label' => 'From', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('excludeDateEnd', DateTimeType::class, ['required' => false, 'label' => 'To', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('actions', FormActionsType::class, ['buttons' => ['filter' => ['type' => SubmitType::class], 'reset' => ['type' => ResetType::class]]]);
    }

    public function getName()
    {
        return 'profile_filter';
    }

    /**
     * CSRF protection is useless for searching and makes it impossible
     * to send urls with filters to other persons, so better disable it.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false
            ]
        );
    }
}
