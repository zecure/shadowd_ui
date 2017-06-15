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
            ->add('includeProfileIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('includeServerIPs', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
            ->add('includeNames', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
            ->add('includeMode', ChoiceType::class, array('required' => false, 'label' => 'Mode', 'choices' => array('1' => 'Active', '2' => 'Passive', '3' => 'Learning')))
            ->add('includeDateStart', DateTimeType::class, array('required' => false, 'label'  => 'From', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includeDateEnd', DateTimeType::class, array('required' => false, 'label'  => 'To', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeProfileIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('excludeServerIPs', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
            ->add('excludeNames', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
            ->add('excludeMode', ChoiceType::class, array('required' => false, 'label' => 'Mode', 'choices' => array('1' => 'Active', '2' => 'Passive', '3' => 'Learning')))
            ->add('excludeDateStart', DateTimeType::class, array('required' => false, 'label'  => 'From', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeDateEnd', DateTimeType::class, array('required' => false, 'label'  => 'To', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('actions', FormActionsType::class, array('buttons' => array('filter' => array('type' => SubmitType::class), 'reset' => array('type' => ResetType::class))));
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
            array(
                'csrf_protection' => false
            )
        );
    }
}
