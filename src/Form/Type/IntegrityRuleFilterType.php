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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class IntegrityRuleFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction('#')
            ->add('includeRuleIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Rule ID'])
            ->add('includeProfileIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'])
            ->add('includeStatus', ChoiceType::class, ['required' => false, 'label' => 'Status', 'choices' => ['1' => 'Active', '2' => 'Inactive', '3' => 'Pending']])
            ->add('includeCallers', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'])
            ->add('includeAlgorithms', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Algorithm'])
            ->add('includeDigests', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Digest'])
            ->add('includeDateStart', DateTimeType::class, ['required' => false, 'label' => 'From', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('includeDateEnd', DateTimeType::class, ['required' => false, 'label' => 'To', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('includeConflict', CheckboxType::class, ['required' => false, 'label' => 'Has conflict'])
            ->add('excludeRuleIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Rule ID'])
            ->add('excludeProfileIds', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'])
            ->add('excludeStatus', ChoiceType::class, ['required' => false, 'label' => 'Status', 'choices' => ['1' => 'Active', '2' => 'Inactive', '3' => 'Pending']])
            ->add('excludeCallers', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'])
            ->add('excludeAlgorithms', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Algorithm'])
            ->add('excludeDigests', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Digest'])
            ->add('excludeDateStart', DateTimeType::class, ['required' => false, 'label' => 'From', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('excludeDateEnd', DateTimeType::class, ['required' => false, 'label' => 'To', 'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute']])
            ->add('excludeConflict', CheckboxType::class, ['required' => false, 'label' => 'Has conflict'])
            ->add('actions', FormActionsType::class, ['buttons' => ['filter' => ['type' => SubmitType::class], 'reset' => ['type' => ResetType::class]]]);
    }

    public function getName()
    {
        return 'integrity_rule_filter';
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
